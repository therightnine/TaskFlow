@extends('layouts.contributeur_layout')

@section('title', 'Contributeur | Dashboard')

@section('content')

<!-- TASKS SECTION - Modern Card Grid -->
<div class="bg-white rounded-3xl shadow-lg p-6 mb-6 border border-gray-100">

    <!-- Header with Stats -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Liste de mes tâches</h2>
            <p class="text-sm text-gray-500 mt-1">
                {{ $tasks->count() }} tâche{{ $tasks->count() > 1 ? 's' : '' }} assignée{{ $tasks->count() > 1 ? 's' : '' }}
            </p>
        </div>

        <!-- Quick Stats -->
        <div class="flex gap-3">
            @php
                $highPriority = $tasks->where('priorite', 'high')->count();
                $mediumPriority = $tasks->where('priorite', 'medium')->count();
                $lowPriority = $tasks->where('priorite', 'low')->count();
            @endphp
            <div class="flex items-center gap-1 text-xs font-medium px-3 py-1.5 bg-red-50 text-red-700 rounded-full">
                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                {{ $highPriority }} Haute
            </div>
            <div class="flex items-center gap-1 text-xs font-medium px-3 py-1.5 bg-yellow-50 text-yellow-700 rounded-full">
                <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                {{ $mediumPriority }} Moyenne
            </div>
            <div class="flex items-center gap-1 text-xs font-medium px-3 py-1.5 bg-green-50 text-green-700 rounded-full">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                {{ $lowPriority }} Basse
            </div>
        </div>
    </div>

    @if($tasks->count())
        <!-- Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[600px] overflow-y-auto p-1">

            @foreach($tasks as $task)
                @php
                    $priorityColors = [
                        'high' => ['bg' => 'bg-gradient-to-br from-red-50 to-red-100', 'border' => 'border-red-200', 'badge' => 'bg-red-500', 'text' => 'text-red-700'],
                        'medium' => ['bg' => 'bg-gradient-to-br from-yellow-50 to-yellow-100', 'border' => 'border-yellow-200', 'badge' => 'bg-yellow-500', 'text' => 'text-yellow-700'],
                        'low' => ['bg' => 'bg-gradient-to-br from-green-50 to-green-100', 'border' => 'border-green-200', 'badge' => 'bg-green-500', 'text' => 'text-green-700'],
                    ];
                    $colors = $priorityColors[$task->priorite] ?? $priorityColors['low'];

                    $daysUntilDeadline = $task->deadline ? \Carbon\Carbon::parse($task->deadline)->diffInDays(now(), false) : null;
                    $isOverdue = $daysUntilDeadline !== null && $daysUntilDeadline < 0;
                    $isDueSoon = $daysUntilDeadline !== null && $daysUntilDeadline >= 0 && $daysUntilDeadline <= 3;
                @endphp

                <div class="group relative {{ $colors['bg'] }} border-2 {{ $colors['border'] }} rounded-2xl p-5 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 cursor-pointer overflow-hidden">

                    <!-- Priority Indicator Strip -->
                    <div class="absolute top-0 left-0 w-full h-1.5 {{ $colors['badge'] }}"></div>

                    <!-- Header: Priority Badge & Menu -->
                    <div class="flex justify-between items-start mb-3 mt-1">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-white/80 {{ $colors['text'] }} backdrop-blur-sm shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full {{ $colors['badge'] }} animate-pulse"></span>
                            {{ $task->priorite }}
                        </span>

                        <!-- Deadline Badge -->
                        @if($task->deadline)
                            <span class="flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-lg
                                {{ $isOverdue ? 'bg-red-500 text-white' : ($isDueSoon ? 'bg-orange-500 text-white' : 'bg-white/80 text-gray-600') }} shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $isOverdue ? 'En retard' : \Carbon\Carbon::parse($task->deadline)->format('d M') }}
                            </span>
                        @endif
                    </div>

                    <!-- Task Title -->
                    <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2 group-hover:text-cyan-700 transition-colors">
                        {{ $task->nom_tache }}
                    </h3>

                    <!-- Project Tag -->
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-white/60 flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-600 truncate">
                            {{ $task->projet->nom ?? ' assigné' }}
                        </span>
                    </div>

                    <!-- Footer: Actions -->
                    <div class="flex items-center justify-between pt-3 border-t border-black/5">
                        <div class="flex -space-x-2">
                            <!-- Avatar placeholder -->
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 border-2 border-white flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                        </div>

                        <button onclick="openTaskModal({{ $task->id }})" class="text-sm font-semibold text-cyan-600 hover:text-cyan-800 flex items-center gap-1 group-hover:gap-2 transition-all">
                            Voir détails
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Hover Effect Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none rounded-2xl"></div>
                </div>
            @endforeach

        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">Aucune tâche assignée</h3>
            <p class="text-gray-500 text-sm">Vous n'avez pas de tâches en cours pour le moment.</p>
        </div>
    @endif

</div>

<!-- BOTTOM SECTION: Charts & Activity -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    <!-- PIE CHART -->
    <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-lg text-gray-800">Répartition de mes tâches</h2>
            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">Par statut</span>
        </div>
        <div class="relative h-64">
            <canvas id="tasksChart"></canvas>
        </div>
    </div>

    <!-- ACTIVITY -->
    <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-lg text-gray-800">Activité récente</h2>
            <span class="text-xs text-cyan-600 bg-cyan-50 px-2 py-1 rounded-lg font-medium">Temps réel</span>
        </div>

        <div class="space-y-4 max-h-64 overflow-y-auto pr-2">
            @forelse ($recentTasks as $task)
                <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-100 to-blue-100 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $task->nom_tache }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Mis à jour {{ $task->updated_at ? \Carbon\Carbon::parse($task->updated_at)->diffForHumans() : '—' }}</p>
                    </div>
                    <span class="text-xs text-gray-400 flex-shrink-0">
                        {{ $task->updated_at ? \Carbon\Carbon::parse($task->updated_at)->format('H:i') : '' }}
                    </span>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400">
                    <p class="text-sm">Aucune activité récente</p>
                </div>
            @endforelse
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
            type: 'doughnut',
            data: {
                labels: labels.length ? labels : ['Aucune donnée'],
                datasets: [{
                    data: values.length ? values : [1],
                    backgroundColor: ['#22c55e', '#f59e0b', '#3b82f6', '#ef4444', '#a855f7', '#eab308'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    })();
</script>

@endsection
