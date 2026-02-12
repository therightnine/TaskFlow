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
    $today = Carbon::today();
    $in7Days = Carbon::today()->addDays(7);

    $totalUsers = DB::table('users')->count();
    $totalRoles = DB::table('roles')->count();
    $totalPlans = DB::table('abonnements')->count();
    $totalProjects = DB::table('projets')->count();
    $totalTasks = DB::table('taches')->count();

    $activeSubscriptions = DB::table('user_abonnement')
        ->whereDate('date_debut', '<=', $today)
        ->where(function ($q) use ($today) {
            $q->whereNull('date_fin')
                ->orWhereDate('date_fin', '>=', $today);
        })
        ->count();

    $expiringSoon = DB::table('user_abonnement')
        ->whereNotNull('date_fin')
        ->whereDate('date_fin', '>=', $today)
        ->whereDate('date_fin', '<=', $in7Days)
        ->count();

    $monthlyRecurringRevenue = DB::table('user_abonnement')
        ->join('abonnements', 'abonnements.id', '=', 'user_abonnement.id_abonnement')
        ->whereDate('user_abonnement.date_debut', '<=', $today)
        ->where(function ($q) use ($today) {
            $q->whereNull('user_abonnement.date_fin')
                ->orWhereDate('user_abonnement.date_fin', '>=', $today);
        })
        ->sum('abonnements.prix');

    $subscriptionRows = DB::table('user_abonnement')
        ->join('abonnements', 'user_abonnement.id_abonnement', '=', 'abonnements.id')
        ->whereDate('user_abonnement.date_debut', '<=', $today)
        ->where(function ($q) use ($today) {
            $q->whereNull('user_abonnement.date_fin')
                ->orWhereDate('user_abonnement.date_fin', '>=', $today);
        })
        ->select(
            'abonnements.abonnement',
            DB::raw('COUNT(user_abonnement.id_inscri) AS active_count')
        )
        ->groupBy('abonnements.abonnement')
        ->orderBy('abonnements.abonnement')
        ->get();

    $subscriptionLabels = [];
    $subscriptionValues = [];
    foreach ($subscriptionRows as $row) {
        $subscriptionLabels[] = $row->abonnement ?? 'Inconnu';
        $subscriptionValues[] = (int) $row->active_count;
    }
    $subscriptionTotal = array_sum($subscriptionValues);

    $usersByRoleRows = DB::table('users')
        ->join('roles', 'roles.id', '=', 'users.id_role')
        ->select('roles.role', DB::raw('COUNT(users.id) as total'))
        ->groupBy('roles.role')
        ->orderBy('roles.role')
        ->get();

    $roleLabels = [];
    $roleValues = [];
    foreach ($usersByRoleRows as $row) {
        $roleLabels[] = $row->role;
        $roleValues[] = (int) $row->total;
    }

    $startMonth = Carbon::now()->startOfMonth()->subMonths(5);
    $monthKeys = [];
    $monthLabels = [];
    for ($i = 0; $i < 6; $i++) {
        $d = $startMonth->copy()->addMonths($i);
        $monthKeys[] = $d->format('Y-m');
        $monthLabels[] = $d->translatedFormat('M Y');
    }

    $monthlySubscriptionRows = DB::table('user_abonnement')
        ->select(DB::raw("DATE_FORMAT(date_debut, '%Y-%m') as ym"), DB::raw('COUNT(*) as total'))
        ->whereDate('date_debut', '>=', $startMonth)
        ->groupBy('ym')
        ->pluck('total', 'ym');

    $monthlySubscriptions = [];
    foreach ($monthKeys as $key) {
        $monthlySubscriptions[] = (int) ($monthlySubscriptionRows[$key] ?? 0);
    }

    $monthlyUserRegistrations = array_fill(0, count($monthKeys), 0);
    if (Schema::hasColumn('users', 'created_at')) {
        $monthlyUserRows = DB::table('users')
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as ym"), DB::raw('COUNT(*) as total'))
            ->whereDate('created_at', '>=', $startMonth)
            ->groupBy('ym')
            ->pluck('total', 'ym');

        foreach ($monthKeys as $idx => $key) {
            $monthlyUserRegistrations[$idx] = (int) ($monthlyUserRows[$key] ?? 0);
        }
    }

    $recentSubscriptionActivity = DB::table('user_abonnement')
        ->join('users', 'users.id', '=', 'user_abonnement.id_inscri')
        ->join('abonnements', 'abonnements.id', '=', 'user_abonnement.id_abonnement')
        ->orderByDesc('user_abonnement.date_debut')
        ->limit(8)
        ->get([
            'users.prenom',
            'users.nom',
            'abonnements.abonnement',
            'user_abonnement.date_debut',
            'user_abonnement.date_fin',
        ])
        ->map(function ($item) {
            $fullName = trim(($item->prenom ?? '') . ' ' . ($item->nom ?? ''));
            $startsAt = Carbon::parse($item->date_debut);
            $endsAt = $item->date_fin ? Carbon::parse($item->date_fin)->format('d/m/Y') : 'Illimite';

            return [
                'title' => 'Abonnement active',
                'message' => "{$fullName} a pris le plan {$item->abonnement} (fin: {$endsAt}).",
                'time' => $startsAt,
            ];
        });

    return view('dashboard.admin', compact(
        'user',
        'totalUsers',
        'totalRoles',
        'totalPlans',
        'totalProjects',
        'totalTasks',
        'activeSubscriptions',
        'expiringSoon',
        'monthlyRecurringRevenue',
        'subscriptionLabels',
        'subscriptionValues',
        'subscriptionTotal',
        'roleLabels',
        'roleValues',
        'monthLabels',
        'monthlySubscriptions',
        'monthlyUserRegistrations',
        'recentSubscriptionActivity'
    ));
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

