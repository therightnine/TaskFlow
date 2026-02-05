
@extends('layouts.contributeur_layout')

@section('title', 'Contributeur | Dashboard')

@section('content')
<div class="space-y-6">

    {{-- APERÇU --}}
    <div class="bg-white rounded-2xl p-6 shadow">
        <h2 class="font-semibold text-lg text-slate-900 mb-1">Aperçu de mes tâches</h2>
        <p class="text-sm text-slate-400 mb-6">Aujourd’hui</p>

        {{-- CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-cyan-100 rounded-2xl p-5">
                <h3 class="text-xl font-bold">{{ $totalTasks }}</h3>
                <p class="text-sm text-slate-600 mt-1">Tâches assignées</p>
            </div>

            <div class="bg-amber-100 rounded-2xl p-5">
                <h3 class="text-xl font-bold">{{ $inProgressTasks }}</h3>
                <p class="text-sm text-slate-600 mt-1">En cours</p>
            </div>

            <div class="bg-emerald-100 rounded-2xl p-5">
                <h3 class="text-xl font-bold">{{ $completedTasksCount }}</h3>
                <p class="text-sm text-slate-600 mt-1">Terminées</p>
            </div>

            <div class="bg-rose-100 rounded-2xl p-5">
                <h3 class="text-xl font-bold">{{ $overdueTasks->count() }}</h3>
                <p class="text-sm text-slate-600 mt-1">En retard</p>
            </div>
        </div>
    </div>

    {{-- LISTE DES TÂCHES --}}
    <div class="bg-white rounded-2xl p-6 shadow">
        <h2 class="font-semibold text-lg mb-4">Liste de mes tâches</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="border-b text-slate-500">
                    <tr>
                        <th class="py-2 text-left">Tâche</th>
                        <th class="text-left">Projet</th>
                        <th>Priorité</th>
                        <th>Statut</th>
                        <th>Deadline</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($tasks as $task)
                        <tr>
                            <td class="py-3 font-medium">{{ $task->nom_tache }}</td>
                            <td>{{ $task->projet->nom_projet ?? '—' }}</td>
                            <td>
                                @if ($task->priorite === 'high')
                                    <span class="text-red-600">Haute</span>
                                @elseif ($task->priorite === 'medium')
                                    <span class="text-yellow-600">Moyenne</span>
                                @else
                                    <span class="text-green-600">Basse</span>
                                @endif
                            </td>
                            <td>{{ $task->etat->etat ?? '—' }}</td>
                            <td>{{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d/m/Y') : '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-400 py-6">Aucune tâche trouvée</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- BAS DE PAGE --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- PIE CHART --}}
        <div class="bg-white rounded-2xl p-6 shadow">
            <h2 class="font-semibold text-lg mb-4">Répartition de mes tâches</h2>
            <canvas id="tasksChart"></canvas>
        </div>

        {{-- ACTIVITÉ --}}
        <div class="bg-white rounded-2xl p-6 shadow">
            <h2 class="font-semibold text-lg mb-4">Activité récente</h2>
            <ul class="space-y-3 text-sm">
                @forelse ($recentTasks as $task)
                    <li class="flex justify-between">
                        <span>{{ $task->nom_tache }}</span>
                        <span class="text-slate-400">
    {{ $task->updated_at ? \Carbon\Carbon::parse($task->updated_at)->diffForHumans() : '—' }}
</span>

                    </li>
                @empty
                    <li class="text-slate-400">Aucune activité récente</li>
                @endforelse
            </ul>
        </div>

    </div>
</div>

{{-- Chart.js rendering (single instance) --}}
<script>
    (function () {
        const labels = @json(array_keys($tasksByStatus ?? []));
        const values = @json(array_values($tasksByStatus ?? []));


        const ctx = document.getElementById('tasksChart');
        if (!ctx) return;

        new Chart(ctx.getContext('2d'), {
            type: 'pie',
            data: {
                labels: labels.length ? labels : ['Aucune donnée'],
                datasets: [{
                    data: values.length ? values : [1],
                    backgroundColor: ['#22c55e', '#f59e0b', '#3b82f6', '#ef4444', '#a855f7', '#eab308']
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });
    })();
</script>
@endsection
