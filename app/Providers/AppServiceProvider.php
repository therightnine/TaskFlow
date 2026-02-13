<?php

namespace App\Providers;

use App\Models\Projet;
use App\Models\Tache;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    private bool $hasProjetContributeurCreatedAt = false;
    private bool $hasProjetSuperviseurCreatedAt = false;
    private bool $hasTacheContributeurCreatedAt = false;

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->initSchemaCapabilities();

        View::composer([
            'layouts.chef_layout',
            'layouts.superviseur_layout',
            'layouts.contributeur_layout',
            'layouts.admin_layout',
        ], function ($view) {
            static $resolved = false;
            static $sharedPayload = null;

            if ($resolved && is_array($sharedPayload)) {
                $view->with($sharedPayload);
                return;
            }

            $notifications = collect();
            $recentTasks = collect();

            if ($user = Auth::user()) {
                $notifications = $this->buildNotifications($user);
                $recentTasks = $notifications;
            }

            $sharedPayload = [
                'notifications' => $notifications,
                'recentTasks' => $recentTasks,
            ];
            $resolved = true;

            $view->with($sharedPayload);
        });
    }

    private function buildNotifications($user): Collection
    {
        $roleId = (int) ($user->id_role ?? 0);

        if ($roleId === 1) {
            return $this->notificationsForAdmin();
        }

        if ($roleId === 3) {
            return $this->notificationsForCreateur($user->id);
        }

        if ($roleId === 2) {
            return $this->notificationsForSuperviseur($user->id);
        }

        if ($roleId === 4) {
            return $this->notificationsForContributeur($user->id);
        }

        return collect();
    }

    private function notificationsForAdmin(): Collection
    {
        $now = Carbon::now();
        $notifications = collect();

        $recentUsers = DB::table('users')
            ->orderByDesc('id')
            ->limit(4)
            ->get(['id', 'prenom', 'nom', 'email']);

        foreach ($recentUsers as $index => $user) {
            $fullName = trim(($user->prenom ?? '') . ' ' . ($user->nom ?? ''));
            $notifications->push(
                $this->makeNotification(
                    'user',
                    'Nouvel utilisateur',
                    ($fullName !== '' ? $fullName : ($user->email ?? 'Utilisateur')) . ' a ete ajoute.',
                    $now->copy()->subMinutes($index),
                    route('admin.utilisateurs.index')
                )
            );
        }

        $recentAbonnementsQuery = DB::table('user_abonnement')
            ->join('users', 'users.id', '=', 'user_abonnement.id_inscri')
            ->join('abonnements', 'abonnements.id', '=', 'user_abonnement.id_abonnement');

        if (Schema::hasColumn('user_abonnement', 'date_debut')) {
            $recentAbonnementsQuery->orderByDesc('user_abonnement.date_debut');
        }

        if (Schema::hasColumn('user_abonnement', 'id_inscri')) {
            $recentAbonnementsQuery->orderByDesc('user_abonnement.id_inscri');
        }

        $recentAbonnements = $recentAbonnementsQuery
            ->limit(4)
            ->get([
                'users.prenom',
                'users.nom',
                'abonnements.abonnement',
                'user_abonnement.date_debut',
            ]);

        foreach ($recentAbonnements as $row) {
            $fullName = trim(($row->prenom ?? '') . ' ' . ($row->nom ?? ''));
            $time = !empty($row->date_debut) ? Carbon::parse($row->date_debut) : $now;
            $notifications->push(
                $this->makeNotification(
                    'subscription',
                    'Nouvel abonnement',
                    ($fullName !== '' ? $fullName : 'Un utilisateur') . " a choisi l'offre {$row->abonnement}.",
                    $time,
                    route('admin.abonnements.gest_abonnements')
                )
            );
        }

        return $this->limitAndSort($notifications);
    }

    private function notificationsForCreateur(int $userId): Collection
    {
        $projects = Projet::with(['contributors', 'taches.etat'])
            ->where('id_user', $userId)
            ->get();

        $projectIds = $projects->pluck('id');
        $notifications = collect();

        if ($projectIds->isNotEmpty()) {
            if ($this->hasProjetContributeurCreatedAt) {
                $recentAssignments = DB::table('projet_contributeur')
                    ->join('users', 'users.id', '=', 'projet_contributeur.user_id')
                    ->join('projets', 'projets.id', '=', 'projet_contributeur.projet_id')
                    ->whereIn('projet_contributeur.projet_id', $projectIds)
                    ->where('projet_contributeur.created_at', '>=', Carbon::now()->subDays(7))
                    ->orderByDesc('projet_contributeur.created_at')
                    ->limit(4)
                    ->get([
                        'users.prenom',
                        'users.nom',
                        'projets.nom_projet',
                        'projet_contributeur.created_at',
                    ]);

                foreach ($recentAssignments as $assignment) {
                    $fullName = trim(($assignment->prenom ?? '') . ' ' . ($assignment->nom ?? ''));
                    $notifications->push(
                        $this->makeNotification(
                            'assignment',
                            'Nouveau contributeur assigne',
                            "{$fullName} a ete assigne au projet {$assignment->nom_projet}.",
                            Carbon::parse($assignment->created_at),
                            route('projects.index')
                        )
                    );
                }
            } else {
                foreach ($projects->filter(fn ($project) => $project->contributors->isNotEmpty())->take(3) as $project) {
                    $notifications->push(
                        $this->makeNotification(
                            'assignment',
                            'Contributeurs sur projet',
                            "{$project->contributors->count()} contributeur(s) sont assignes au projet {$project->nom_projet}.",
                            Carbon::now(),
                            route('projects.index')
                        )
                    );
                }
            }

            $notifications = $notifications
                ->merge($this->taskDeadlineNotifications($projectIds, null))
                ->merge($this->completionNotifications($projects));
        }

        return $this->limitAndSort($notifications);
    }

    private function notificationsForSuperviseur(int $userId): Collection
    {
        $projects = Projet::with(['taches.etat'])
            ->whereHas('superviseurs', fn ($query) => $query->where('users.id', $userId))
            ->get();

        $projectIds = $projects->pluck('id');
        $notifications = collect();

        if ($projectIds->isNotEmpty()) {
            if ($this->hasProjetSuperviseurCreatedAt) {
                $assignedProjects = DB::table('projet_superviseur')
                    ->join('projets', 'projets.id', '=', 'projet_superviseur.projet_id')
                    ->where('projet_superviseur.user_id', $userId)
                    ->where('projet_superviseur.created_at', '>=', Carbon::now()->subDays(7))
                    ->orderByDesc('projet_superviseur.created_at')
                    ->limit(4)
                    ->get(['projets.nom_projet', 'projet_superviseur.created_at']);

                foreach ($assignedProjects as $assignment) {
                    $notifications->push(
                        $this->makeNotification(
                            'assignment',
                            'Projet assigne',
                            "Vous etes assigne au projet {$assignment->nom_projet}.",
                            Carbon::parse($assignment->created_at),
                            route('projects.index')
                        )
                    );
                }
            } else {
                foreach ($projects->take(3) as $project) {
                    $notifications->push(
                        $this->makeNotification(
                            'assignment',
                            'Projet supervise',
                            "Vous supervisez le projet {$project->nom_projet}.",
                            Carbon::now(),
                            route('projects.index')
                        )
                    );
                }
            }

            $notifications = $notifications
                ->merge($this->taskDeadlineNotifications($projectIds, null))
                ->merge($this->completionNotifications($projects));
        }

        return $this->limitAndSort($notifications);
    }

    private function notificationsForContributeur(int $userId): Collection
    {
        $projectIds = Projet::whereHas('contributors', fn ($query) => $query->where('users.id', $userId))
            ->pluck('projets.id');

        $assignedTasks = Tache::with(['projet', 'etat'])
            ->whereHas('contributors', fn ($query) => $query->where('users.id', $userId))
            ->get();

        $notifications = collect();

        if ($projectIds->isNotEmpty()) {
            if ($this->hasProjetContributeurCreatedAt) {
                $assignedProjects = DB::table('projet_contributeur')
                    ->join('projets', 'projets.id', '=', 'projet_contributeur.projet_id')
                    ->where('projet_contributeur.user_id', $userId)
                    ->where('projet_contributeur.created_at', '>=', Carbon::now()->subDays(7))
                    ->orderByDesc('projet_contributeur.created_at')
                    ->limit(3)
                    ->get(['projets.nom_projet', 'projet_contributeur.created_at']);

                foreach ($assignedProjects as $project) {
                    $notifications->push(
                        $this->makeNotification(
                            'assignment',
                            'Projet assigne',
                            "Vous avez ete ajoute au projet {$project->nom_projet}.",
                            Carbon::parse($project->created_at),
                            route('projects.index')
                        )
                    );
                }
            }
        }

        if ($assignedTasks->isNotEmpty()) {
            if ($this->hasTacheContributeurCreatedAt) {
                $taskAssignments = DB::table('tache_contributeur')
                    ->join('taches', 'taches.id', '=', 'tache_contributeur.id_tache')
                    ->where('tache_contributeur.id_user', $userId)
                    ->where('tache_contributeur.created_at', '>=', Carbon::now()->subDays(7))
                    ->orderByDesc('tache_contributeur.created_at')
                    ->limit(4)
                    ->get(['taches.nom_tache', 'tache_contributeur.created_at']);

                foreach ($taskAssignments as $task) {
                    $notifications->push(
                        $this->makeNotification(
                            'assignment',
                            'Tache assignee',
                            "La tache {$task->nom_tache} vous a ete assignee.",
                            Carbon::parse($task->created_at),
                            route('tasks.index')
                        )
                    );
                }
            } else {
                foreach ($assignedTasks->take(3) as $task) {
                    $notifications->push(
                        $this->makeNotification(
                            'assignment',
                            'Tache assignee',
                            "Vous etes assigne a la tache {$task->nom_tache}.",
                            $this->resolveTaskTimestamp($task),
                            route('tasks.index')
                        )
                    );
                }
            }
        }

        $notifications = $notifications->merge(
            $this->taskDeadlineNotifications(
                collect(),
                $assignedTasks
            )
        );

        return $this->limitAndSort($notifications);
    }

    private function taskDeadlineNotifications(Collection $projectIds, ?Collection $tasks): Collection
    {
        $today = Carbon::today();

        $query = Tache::with(['projet', 'etat']);

        if ($tasks !== null) {
            $taskIds = $tasks->pluck('id');

            if ($taskIds->isEmpty()) {
                return collect();
            }

            $query->whereIn('id', $taskIds);
        } elseif ($projectIds->isNotEmpty()) {
            $query->whereIn('id_projet', $projectIds);
        } else {
            return collect();
        }

        $items = $query->get();

        $overdue = $items
            ->filter(fn ($task) => !$this->isCompletedTask($task))
            ->filter(fn ($task) => !empty($task->deadline) && Carbon::parse($task->deadline)->lt($today))
            ->sortBy('deadline')
            ->take(3);

        $upcoming = $items
            ->filter(fn ($task) => !$this->isCompletedTask($task))
            ->filter(function ($task) use ($today) {
                if (empty($task->deadline)) {
                    return false;
                }

                $deadline = Carbon::parse($task->deadline);
                return $deadline->betweenIncluded($today, $today->copy()->addDays(3));
            })
            ->sortBy('deadline')
            ->take(3);

        $notifications = collect();

        foreach ($overdue as $task) {
            $projectName = $task->projet->nom_projet ?? 'projet inconnu';
            $notifications->push(
                $this->makeNotification(
                    'overdue',
                    'Tache en retard',
                    "{$task->nom_tache} ({$projectName}) est en retard.",
                    $this->resolveTaskTimestamp($task),
                    route('tasks.index')
                )
            );
        }

        foreach ($upcoming as $task) {
            $projectName = $task->projet->nom_projet ?? 'projet inconnu';
            $deadline = Carbon::parse($task->deadline)->format('d/m/Y');
            $notifications->push(
                $this->makeNotification(
                    'upcoming',
                    'Echeance proche',
                    "{$task->nom_tache} ({$projectName}) arrive a echeance le {$deadline}.",
                    $this->resolveTaskTimestamp($task),
                    route('tasks.index')
                )
            );
        }

        return $notifications;
    }

    private function completionNotifications(Collection $projects): Collection
    {
        $notifications = collect();

        foreach ($projects as $project) {
            $tasks = $project->taches ?? collect();

            if ($tasks->isEmpty()) {
                continue;
            }

            if ($tasks->every(fn ($task) => $this->isCompletedTask($task))) {
                $notifications->push(
                    $this->makeNotification(
                        'completion',
                        'Projet termine',
                        "Toutes les taches du projet {$project->nom_projet} sont terminees.",
                        Carbon::now(),
                        route('projects.index')
                    )
                );
            }

            foreach ($tasks->filter(fn ($task) => $this->isCompletedTask($task))->take(2) as $task) {
                $notifications->push(
                    $this->makeNotification(
                        'completion',
                        'Tache terminee',
                        "{$task->nom_tache} est terminee.",
                        $this->resolveTaskTimestamp($task),
                        route('tasks.index')
                    )
                );
            }
        }

        return $notifications;
    }

    private function isCompletedTask(Tache $task): bool
    {
        $status = Str::of((string) optional($task->etat)->etat)->lower()->ascii()->value();

        if ($status !== '' && Str::contains($status, ['termine', 'complete', 'completed', 'done'])) {
            return true;
        }

        return (int) $task->id_etat === 3;
    }

    private function resolveTaskTimestamp(Tache $task): Carbon
    {
        if (!empty($task->updated_at)) {
            return Carbon::parse($task->updated_at);
        }

        if (!empty($task->created_at)) {
            return Carbon::parse($task->created_at);
        }

        return Carbon::now();
    }

    private function makeNotification(
        string $type,
        string $title,
        string $message,
        Carbon $time,
        ?string $url = null
    ): array {
        return [
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'time' => $time,
            'url' => $url,
        ];
    }

    private function limitAndSort(Collection $notifications): Collection
    {
        return $notifications
            ->sortByDesc(fn ($item) => $item['time']->timestamp ?? 0)
            ->values()
            ->take(8);
    }

    private function initSchemaCapabilities(): void
    {
        $resolver = function () {
            return [
                'projet_contributeur_created_at' => Schema::hasTable('projet_contributeur')
                    && Schema::hasColumn('projet_contributeur', 'created_at'),
                'projet_superviseur_created_at' => Schema::hasTable('projet_superviseur')
                    && Schema::hasColumn('projet_superviseur', 'created_at'),
                'tache_contributeur_created_at' => Schema::hasTable('tache_contributeur')
                    && Schema::hasColumn('tache_contributeur', 'created_at'),
            ];
        };

        try {
            $capabilities = cache()->remember('schema_capabilities_v1', now()->addHours(12), $resolver);
        } catch (\Throwable $e) {
            // Fallback when cache store is unavailable (e.g. database driver without cache table).
            $capabilities = $resolver();
        }

        $this->hasProjetContributeurCreatedAt = (bool) ($capabilities['projet_contributeur_created_at'] ?? false);
        $this->hasProjetSuperviseurCreatedAt = (bool) ($capabilities['projet_superviseur_created_at'] ?? false);
        $this->hasTacheContributeurCreatedAt = (bool) ($capabilities['tache_contributeur_created_at'] ?? false);
    }
}
