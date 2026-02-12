<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use App\Models\Tache;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD CHEF
    |--------------------------------------------------------------------------
    */
    public function chef()
    {
        $user = auth()->user();
        $today = Carbon::today();
        $weekStart = Carbon::now()->subDays(7);

        $projects = Projet::with(['owner', 'superviseurs.role', 'contributors.role', 'taches.etat'])
            ->where('id_user', $user->id)
            ->get();

        $projectIds = $projects->pluck('id');
        $allTasks = $projects->flatMap(fn ($project) => $project->taches)->values();

        $activeProjects = $projects->filter(function ($project) {
            if (!$project->etat) {
                return true;
            }

            return !$this->isCompletedStatus($project->etat->etat);
        })->count();

        $openTasks = $allTasks->filter(fn ($task) => !$this->isCompletedTask($task));

        $tasksDueToday = $openTasks
            ->filter(fn ($task) => !empty($task->deadline) && Carbon::parse($task->deadline)->isSameDay($today))
            ->count();

        $overdueTasksCount = $openTasks
            ->filter(fn ($task) => !empty($task->deadline) && Carbon::parse($task->deadline)->lt($today))
            ->count();

        $completedProjects = $projects->filter(function ($project) {
            $tasks = $project->taches ?? collect();
            if ($tasks->isEmpty()) {
                return false;
            }

            return $tasks->every(fn ($task) => $this->isCompletedTask($task));
        })->count();

        $completedTasksThisWeek = $allTasks
            ->filter(fn ($task) => $this->isCompletedTask($task))
            ->filter(function ($task) use ($weekStart) {
                $time = $this->taskEventTime($task);
                return $time && $time->gte($weekStart);
            })
            ->count();

        $tasksByStatus = $allTasks
            ->groupBy(function ($task) {
                $status = optional($task->etat)->etat;
                return $status ?: 'Inconnu';
            })
            ->map(fn ($group) => $group->count())
            ->toArray();

        $completedTasks = array_fill(0, 12, 0);
        $newTasks = array_fill(0, 12, 0);
        $overdueTasks = array_fill(0, 12, 0);
        $currentYear = (int) $today->year;

        foreach ($allTasks as $task) {
            $eventDate = $this->taskEventTime($task);
            if (!$eventDate) {
                continue;
            }
            if ((int) $eventDate->year !== $currentYear) {
                continue;
            }

            $index = (int) $eventDate->month - 1;
            $newTasks[$index]++;

            if ($this->isCompletedTask($task)) {
                $completedTasks[$index]++;
            }

            if (!$this->isCompletedTask($task) && !empty($task->deadline) && Carbon::parse($task->deadline)->lt($today)) {
                $overdueTasks[$index]++;
            }
        }

        // Team members using the same relationship logic as EquipeController.
        $teamMembers = $projects->flatMap(function ($project) {
            $members = collect();

            if ($project->owner) {
                $members->push($project->owner);
            }

            $members = $members
                ->merge($project->superviseurs ?? collect())
                ->merge($project->contributors ?? collect());

            return $members;
        })->unique('id')->values();

        $teamActivityByEquipe = collect();
        $defaultEquipeId = null;

        if ($projectIds->isNotEmpty()) {
            $projectIdsArray = $projectIds->all();

            $contributorAssignmentsByProject = collect();
            if (Schema::hasTable('projet_contributeur') && Schema::hasColumn('projet_contributeur', 'created_at')) {
                $contributorAssignmentsByProject = DB::table('projet_contributeur')
                    ->join('users', 'users.id', '=', 'projet_contributeur.user_id')
                    ->whereIn('projet_id', $projectIdsArray)
                    ->where('created_at', '>=', $weekStart)
                    ->orderByDesc('created_at')
                    ->get([
                        'projet_contributeur.projet_id',
                        'projet_contributeur.created_at',
                        'users.prenom',
                        'users.nom',
                    ])
                    ->groupBy('projet_id');
            }

            $supervisorAssignmentsByProject = collect();
            if (Schema::hasTable('projet_superviseur') && Schema::hasColumn('projet_superviseur', 'created_at')) {
                $supervisorAssignmentsByProject = DB::table('projet_superviseur')
                    ->join('users', 'users.id', '=', 'projet_superviseur.user_id')
                    ->whereIn('projet_id', $projectIdsArray)
                    ->where('created_at', '>=', $weekStart)
                    ->orderByDesc('created_at')
                    ->get([
                        'projet_superviseur.projet_id',
                        'projet_superviseur.created_at',
                        'users.prenom',
                        'users.nom',
                    ])
                    ->groupBy('projet_id');
            }

            $taskAssignmentsByProject = collect();
            if (
                Schema::hasTable('tache_contributeur') &&
                Schema::hasColumn('tache_contributeur', 'created_at')
            ) {
                $taskAssignmentsByProject = DB::table('tache_contributeur')
                    ->join('taches', 'taches.id', '=', 'tache_contributeur.id_tache')
                    ->join('users', 'users.id', '=', 'tache_contributeur.id_user')
                    ->whereIn('taches.id_projet', $projectIdsArray)
                    ->where('tache_contributeur.created_at', '>=', $weekStart)
                    ->orderByDesc('tache_contributeur.created_at')
                    ->get([
                        'taches.id_projet',
                        'taches.nom_tache',
                        'tache_contributeur.created_at',
                        'users.prenom',
                        'users.nom',
                    ])
                    ->groupBy('id_projet');
            }

            $teamActivityByEquipe = $projects->map(function ($project) use (
                $weekStart,
                $today,
                $contributorAssignmentsByProject,
                $supervisorAssignmentsByProject,
                $taskAssignmentsByProject
            ) {
                $entries = collect();

                foreach (($contributorAssignmentsByProject[$project->id] ?? collect()) as $assignment) {
                    $name = trim(($assignment->prenom ?? '') . ' ' . ($assignment->nom ?? ''));
                    $entries->push([
                        'type' => 'assignment',
                        'title' => 'Nouveau contributeur',
                        'message' => "{$name} a rejoint l'equipe.",
                        'time' => Carbon::parse($assignment->created_at),
                    ]);
                }

                foreach (($supervisorAssignmentsByProject[$project->id] ?? collect()) as $assignment) {
                    $name = trim(($assignment->prenom ?? '') . ' ' . ($assignment->nom ?? ''));
                    $entries->push([
                        'type' => 'assignment',
                        'title' => 'Nouveau superviseur',
                        'message' => "{$name} supervise maintenant l'equipe.",
                        'time' => Carbon::parse($assignment->created_at),
                    ]);
                }

                foreach (($taskAssignmentsByProject[$project->id] ?? collect()) as $assignment) {
                    $name = trim(($assignment->prenom ?? '') . ' ' . ($assignment->nom ?? ''));
                    $entries->push([
                        'type' => 'assignment',
                        'title' => 'Attribution de tache',
                        'message' => "{$assignment->nom_tache} a ete assignee a {$name}.",
                        'time' => Carbon::parse($assignment->created_at),
                    ]);
                }

                foreach ($project->taches ?? collect() as $task) {
                    $eventTime = $this->taskEventTime($task);
                    if (!$eventTime || $eventTime->lt($weekStart)) {
                        continue;
                    }

                    if ($this->isCompletedTask($task)) {
                        $entries->push([
                            'type' => 'done',
                            'title' => 'Tache terminee',
                            'message' => "{$task->nom_tache} a ete terminee.",
                            'time' => $eventTime,
                        ]);
                        continue;
                    }

                    if (!empty($task->deadline) && Carbon::parse($task->deadline)->lt($today)) {
                        $entries->push([
                            'type' => 'overdue',
                            'title' => 'Tache en retard',
                            'message' => "{$task->nom_tache} est en retard.",
                            'time' => Carbon::parse($task->deadline),
                        ]);
                        continue;
                    }

                    $entries->push([
                        'type' => 'update',
                        'title' => 'Mise a jour de tache',
                        'message' => "{$task->nom_tache} a ete mise a jour.",
                        'time' => $eventTime,
                    ]);
                }

                $entries = $entries
                    ->sortByDesc(fn ($entry) => $entry['time']->timestamp)
                    ->values()
                    ->take(12);

                return [
                    'project_id' => $project->id,
                    'project_name' => $project->nom_projet,
                    'entries' => $entries,
                ];
            })->values();

            $defaultEquipeId = optional($teamActivityByEquipe->first())['project_id'] ?? null;
        }

        return view('dashboard.chef', compact(
            'user',
            'activeProjects',
            'tasksDueToday',
            'overdueTasksCount',
            'completedProjects',
            'completedTasksThisWeek',
            'tasksByStatus',
            'completedTasks',
            'newTasks',
            'overdueTasks',
            'teamMembers',
            'teamActivityByEquipe',
            'defaultEquipeId'
        ));
    }
/*|--------------------------------------------------------------------------
| Dashboard Admin - Abonnements
|--------------------------------------------------------------------------*/
public function admin()
{
    $user = auth()->user();
    $abonnements = DB::table('abonnements')
        ->orderBy('id', 'desc')
        ->paginate(10);

    // Date de référence (aujourd'hui)
    $dateRef = now()->toDateString();

// Récupérer les abonnements actifs
    $rows = DB::table('user_abonnement')
        ->join('abonnements', 'user_abonnement.id_abonnement', '=', 'abonnements.id')
        ->whereDate('user_abonnement.date_debut', '<=', $dateRef)
        ->where(function ($q) use ($dateRef) {
            $q->whereNull('user_abonnement.date_fin')
              ->orWhereDate('user_abonnement.date_fin', '>=', $dateRef);
        })
        ->select(
            'abonnements.abonnement',
            DB::raw('COUNT(user_abonnement.id_inscri) AS active_count')
        )
        ->groupBy('abonnements.abonnement')
        ->orderBy('abonnements.abonnement')
        ->get();

    // Préparer les données pour le graphe
    $labels = [];
    $values = [];
    foreach ($rows as $r) {
        $labels[] = $r->abonnement ?? 'Inconnu';
        $values[] = (int) $r->active_count;
    }
    $total = array_sum($values);

    return view('dashboard.admin', [
        'user' => $user,
        'subscriptionLabels' => $labels,
        'subscriptionValues' => $values,
        'subscriptionTotal'  => $total,
        'abonnements'        => $abonnements,
    ]);
}






    
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD SUPERVISEUR
    |--------------------------------------------------------------------------
    */
    public function supervisor()
    {
        $user = auth()->user();

        // Projects assigned to this supervisor
        $projects = $user->projetsSupervised()
            ->with('taches', 'contributors')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TASK PROGRESS BY MONTH (used for stats)
        |--------------------------------------------------------------------------
        */
        $taskProgressByMonth = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthData = [];

            foreach ($projects as $project) {
                $tasksInMonth = $project->taches->filter(fn ($tache) =>
                    \Carbon\Carbon::parse($tache->created_at)->month === $month
                );

                $totalTasks = $tasksInMonth->count();
                $completedTasks = $tasksInMonth->where('id_etat', 3)->count();

                $monthData[] = [
                    'project_name'     => $project->nom_projet,
                    'total_tasks'      => $totalTasks,
                    'completed_tasks'  => $completedTasks,
                    'progress'         => $totalTasks > 0
                        ? round(($completedTasks / $totalTasks) * 100)
                        : 0,
                ];
            }

            $taskProgressByMonth[$month] = $monthData;
        }

        /*
        |--------------------------------------------------------------------------
        | BAR CHART DATA (COMPLETED / IN PROGRESS / PENDING)
        |--------------------------------------------------------------------------
        */
        $tasksCompletedByMonth  = array_fill(0, 12, 0);
        $tasksInProgressByMonth = array_fill(0, 12, 0);
        $tasksPendingByMonth    = array_fill(0, 12, 0);

        for ($month = 1; $month <= 12; $month++) {
            $index = $month - 1;

            $tasksCompletedByMonth[$index] = Tache::whereIn('id_projet', $projects->pluck('id'))
                ->where('id_etat', 3) // completed
                ->whereMonth('created_at', $month)
                ->count();

            $tasksInProgressByMonth[$index] = Tache::whereIn('id_projet', $projects->pluck('id'))
                ->where('id_etat', 2) // in progress
                ->whereMonth('created_at', $month)
                ->count();

            $tasksPendingByMonth[$index] = Tache::whereIn('id_projet', $projects->pluck('id'))
                ->where('id_etat', 1) // pending
                ->whereMonth('created_at', $month)
                ->count();
        }

        /*
        |--------------------------------------------------------------------------
        | LINE CHART – PROJECT PROGRESS
        |--------------------------------------------------------------------------
        */
        $monthlyProjectProgress = [];

        foreach ($projects as $project) {
            $monthlyData = Tache::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as completed')
                )
                ->where('id_projet', $project->id)
                ->where('id_etat', 3)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->pluck('completed', 'month')
                ->toArray();

            $monthlyArray = array_fill(0, 12, 0);

            foreach ($monthlyData as $m => $count) {
                $monthlyArray[$m - 1] = $count;
            }

            $monthlyProjectProgress[] = [
                'project_name'      => $project->nom_projet,
                'monthly_completed' => $monthlyArray,
            ];
        }

        return view('dashboard.superviseur', compact(
            'user',
            'taskProgressByMonth',
            'monthlyProjectProgress',
            'tasksCompletedByMonth',
            'tasksInProgressByMonth',
            'tasksPendingByMonth'
        ));
    }

    

    
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD CONTRIBUTEUR
    |--------------------------------------------------------------------------
    */
    public function contributeur()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // 🔹 Tâches assignées au contributeur via pivot table
        $tasks = Tache::with(['etat', 'projet'])
            ->whereHas('contributors', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->orderBy('deadline')
            ->get();

        // 🔢 Statistiques
        $totalTasks = $tasks->count();

        $inProgressTasks = $tasks->filter(fn ($t) =>
            $t->etat && $t->etat->etat === 'en cours'
        )->count();

        $completedTasksCount = $tasks->filter(fn ($t) =>
            $t->etat && $t->etat->etat === 'terminé'
        )->count();

        $overdueTasks = $tasks->filter(fn ($t) =>
            $t->deadline < $today &&
            $t->etat &&
            $t->etat->etat !== 'terminé'
        );

        // 📊 Pour Chart.js
        $tasksByStatus = $tasks
            ->groupBy(fn ($t) => $t->etat->etat ?? 'Inconnu')
            ->map(fn ($group) => $group->count())
            ->toArray();

        // 🕒 Activité récente
        $recentTasks = $tasks->sortByDesc('updated_at')->take(5);

        return view('dashboard.contributeur', compact(
            'tasks',
            'totalTasks',
            'inProgressTasks',
            'completedTasksCount',
            'overdueTasks',
            'tasksByStatus',
            'recentTasks'
        ));
    }

    private function isCompletedStatus(?string $status): bool
    {
        $value = Str::of((string) $status)->lower()->ascii()->value();
        if ($value === '') {
            return false;
        }

        return Str::contains($value, ['termine', 'complete', 'completed', 'done']);
    }

    private function isCompletedTask(Tache $task): bool
    {
        if ($this->isCompletedStatus(optional($task->etat)->etat)) {
            return true;
        }

        return (int) $task->id_etat === 3;
    }

    private function taskEventTime(Tache $task): ?Carbon
    {
        if (!empty($task->updated_at)) {
            return Carbon::parse($task->updated_at);
        }

        if (!empty($task->created_at)) {
            return Carbon::parse($task->created_at);
        }

        if (!empty($task->deadline)) {
            return Carbon::parse($task->deadline);
        }

        return null;
    }
}
