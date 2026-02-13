@extends('layouts.chef_layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')


@section('content')

<div class="space-y-6">

    {{-- TOP ROW --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Project Overview --}}
        <div class="xl:col-span-2 bg-white rounded-2xl p-6 shadow">
            {{-- Header --}}
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="font-semibold text-lg text-slate-900">Project Overview</h2>
                    <p class="text-sm text-slate-400 mt-1">Today</p>
                </div>

                <button class="flex items-center gap-2 border border-slate-200 px-4 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 16v-8m0 0l-3 3m3-3l3 3M4 16.5A2.5 2.5 0 016.5 14h11a2.5 2.5 0 012.5 2.5" />
                    </svg>
                    Export
                </button>
            </div>

            {{-- Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                {{-- Active Projects --}}
                <div class="bg-rose-100 rounded-2xl p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="w-10 h-10 rounded-full bg-rose-500 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3v18h18M7 13h3v5H7zm5-4h3v9h-3zm5-6h3v15h-3z" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold">{{ $activeProjects }}</h3>
                    <p class="text-sm text-slate-600 mt-1">Active Projects</p>
                    <p class="text-xs text-blue-600 mt-2">{{ $teamMembers->count() }} membres dans vos equipes</p>
                </div>

                {{-- Tasks Due Today --}}
                <div class="bg-amber-100 rounded-2xl p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="w-10 h-10 rounded-full bg-amber-400 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6M9 8h6m3-5H6a2 2 0 00-2 2v14a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2z" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold">{{ $tasksDueToday }}</h3>
                    <p class="text-sm text-slate-600 mt-1">Tasks Due Today</p>
                    <p class="text-xs text-blue-600 mt-2">Echeances du jour sur vos projets</p>
                </div>

                {{-- Overdue Tasks --}}
                <div class="bg-emerald-100 rounded-2xl p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold">{{ $overdueTasksCount }}</h3>
                    <p class="text-sm text-slate-600 mt-1">Overdue Tasks</p>
                    <p class="text-xs text-blue-600 mt-2">Demandent une action rapide</p>
                </div>

                {{-- Completed Projects --}}
                <div class="bg-violet-100 rounded-2xl p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="w-10 h-10 rounded-full bg-violet-500 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.4-1.4a2 2 0 01-.6-1.4V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold">{{ $completedProjects }}</h3>
                    <p class="text-sm text-slate-600 mt-1">Completed Projects</p>
                    <p class="text-xs text-blue-600 mt-2">{{ $completedTasksThisWeek }} taches terminees cette semaine</p>
                </div>

            </div>
        </div>


        {{-- Performance Line Chart --}}
        
        <div class="bg-white rounded-2xl p-6 shadow">
            <h2 class="font-semibold text-lg mb-4">Project Performance Insights</h2>
            <canvas id="performanceChart" height="180"></canvas>
            
        </div>
    </div>

    @php
        $totalProjects = (int) $activeProjects + (int) $completedProjects;
        $totalTasks = array_sum(array_map('intval', $tasksByStatus ?? []));
        $completionRate = $totalProjects > 0 ? round(($completedProjects / $totalProjects) * 100) : 0;
        $overdueRate = $totalTasks > 0 ? round(($overdueTasksCount / $totalTasks) * 100) : 0;
        $todayRate = $totalTasks > 0 ? round(($tasksDueToday / $totalTasks) * 100) : 0;
        $throughputScore = min(100, (int) $completedTasksThisWeek * 10);
        $statusRows = collect($tasksByStatus ?? [])
            ->sortDesc()
            ->take(4);
    @endphp

    {{-- KPI STRIP --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-4">
        <div class="rounded-2xl p-4 bg-gradient-to-br from-cyan-500 to-cyan-700 text-white shadow transition-all duration-300 hover:-translate-y-1">
            <p class="text-xs uppercase tracking-wide text-slate-200">Execution Rate</p>
            <div class="mt-2 flex items-end justify-between">
                <h3 class="text-3xl font-bold">{{ $completionRate }}%</h3>
                <span class="text-xs bg-white/20 px-2 py-1 rounded-full">{{ $completedProjects }}/{{ $totalProjects ?: 0 }} projets</span>
            </div>
            <div class="mt-3 h-2 rounded-full bg-white/20 overflow-hidden">
                <div class="h-full bg-emerald-300" style="width: {{ $completionRate }}%"></div>
            </div>
        </div>

        <div class="rounded-2xl p-4 bg-white shadow border border-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <p class="text-xs uppercase tracking-wide text-slate-500">Risque Retard</p>
            <div class="mt-2 flex items-end justify-between">
                <h3 class="text-3xl font-bold text-rose-600">{{ $overdueRate }}%</h3>
                <span class="text-xs bg-rose-50 text-rose-600 px-2 py-1 rounded-full">{{ $overdueTasksCount }} en retard</span>
            </div>
            <p class="mt-3 text-xs text-slate-500">Part des taches en retard sur l'ensemble de vos taches.</p>
        </div>

        <div class="rounded-2xl p-4 bg-white shadow border border-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <p class="text-xs uppercase tracking-wide text-slate-500">Charge Du Jour</p>
            <div class="mt-2 flex items-end justify-between">
                <h3 class="text-3xl font-bold text-amber-600">{{ $todayRate }}%</h3>
                <span class="text-xs bg-amber-50 text-amber-700 px-2 py-1 rounded-full">{{ $tasksDueToday }} aujourd'hui</span>
            </div>
            <p class="mt-3 text-xs text-slate-500">Taches avec echeance aujourd'hui par rapport au total.</p>
        </div>

        <div class="rounded-2xl p-4 bg-white shadow border border-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <p class="text-xs uppercase tracking-wide text-slate-500">Weekly Throughput</p>
            <div class="mt-2 flex items-end justify-between">
                <h3 class="text-3xl font-bold text-cyan-700">{{ $completedTasksThisWeek }}</h3>
                <span class="text-xs bg-cyan-50 text-cyan-700 px-2 py-1 rounded-full">score {{ $throughputScore }}/100</span>
            </div>
            <div class="mt-3 h-2 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full bg-cyan-500" style="width: {{ $throughputScore }}%"></div>
            </div>
        </div>
    </div>

    {{-- BOTTOM ROW --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Tasks Pie --}}
        <div class="bg-white rounded-2xl p-6 shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">Tasks</h2>
                <span class="bg-blue-50 text-blue-600 text-sm px-3 py-1 rounded-full">
                    This Week
                </span>
            </div>
            <canvas id="tasksChart" height="200"></canvas>

        </div>


        {{-- Team Activity --}}
        <div class="bg-white rounded-2xl p-6 shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">Team Activity Log</h2>
                <span class="bg-blue-50 text-blue-600 text-sm px-3 py-1 rounded-full">
                    This Week
                </span>
            </div>

            @if($teamActivityByEquipe->isNotEmpty())
                <div class="mb-4">
                    <label for="equipeFilter" class="block text-xs font-semibold text-gray-500 mb-2">Filtrer par equipe</label>
                    <select id="equipeFilter" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        @foreach($teamActivityByEquipe as $equipeLog)
                            <option value="{{ $equipeLog['project_id'] }}">
                                {{ $equipeLog['project_name'] }} ({{ $equipeLog['entries']->count() }} activites)
                            </option>
                        @endforeach
                    </select>
                </div>

                @foreach($teamActivityByEquipe as $equipeLog)
                    <div
                        class="team-log-panel space-y-3 max-h-64 overflow-y-auto pr-2 {{ $equipeLog['project_id'] == $defaultEquipeId ? '' : 'hidden' }}"
                        data-equipe="{{ $equipeLog['project_id'] }}"
                    >
                        @forelse($equipeLog['entries'] as $entry)
                            <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0
                                    {{ $entry['type'] === 'overdue' ? 'bg-red-100 text-red-600' : '' }}
                                    {{ $entry['type'] === 'done' ? 'bg-green-100 text-green-600' : '' }}
                                    {{ $entry['type'] === 'assignment' ? 'bg-blue-100 text-blue-600' : '' }}
                                    {{ in_array($entry['type'], ['update']) ? 'bg-amber-100 text-amber-600' : '' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800">{{ $entry['title'] }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $entry['message'] }}</p>
                                </div>
                                <span class="text-xs text-gray-400 flex-shrink-0">{{ $entry['time']->diffForHumans() }}</span>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-400 text-sm">Aucune activite recente pour cette equipe.</div>
                        @endforelse
                    </div>
                @endforeach
            @else
                <div class="text-center py-8 text-gray-400 text-sm">Aucune equipe trouvee.</div>
            @endif
        </div>

    </div>

    {{-- STATUS SNAPSHOT --}}
    <div class="bg-white rounded-2xl p-6 shadow">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-lg">Task Status Snapshot</h2>
            <span class="bg-slate-100 text-slate-600 text-sm px-3 py-1 rounded-full">
                Temps reel
            </span>
        </div>

        @if($statusRows->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($statusRows as $label => $count)
                    @php
                        $count = (int) $count;
                        $pct = $totalTasks > 0 ? round(($count / $totalTasks) * 100) : 0;
                        $labelKey = \Illuminate\Support\Str::lower((string) $label);
                        $barClass = str_contains($labelKey, 'term') || str_contains($labelKey, 'done') ? 'bg-emerald-500'
                            : (str_contains($labelKey, 'retard') || str_contains($labelKey, 'bloq') ? 'bg-rose-500'
                            : 'bg-cyan-500');
                    @endphp
                    <div class="p-4 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-semibold text-slate-700">{{ $label }}</span>
                            <span class="text-slate-500">{{ $count }} taches</span>
                        </div>
                        <div class="mt-3 h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full {{ $barClass }}" style="width: {{ $pct }}%"></div>
                        </div>
                        <p class="mt-2 text-xs text-slate-500">{{ $pct }}% du portefeuille de taches</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-slate-500">Aucune donnee de statut disponible.</p>
        @endif
    </div>

</div>

{{-- Charts --}}


<script>
    const tasksData = @json($tasksByStatus);

    /* Line Chart */
    new Chart(document.getElementById('performanceChart'), {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [
                {
                    label: 'Completed Tasks',
                    data: @json($completedTasks),
                    borderColor: '#8b5cf6',
                    tension: 0.4
                },
                {
                    label: 'New Tasks',
                    data: @json($newTasks),
                    borderColor: '#ef4444',
                    tension: 0.4
                },
                {
                    label: 'Overdue Tasks',
                    data: @json($overdueTasks),
                    borderColor: '#22c55e',
                    tension: 0.4
                }
            ]
        },
        options: {
            plugins: { legend: { position: 'bottom' } },
            responsive: true
        }
    });


    /* Tasks Pie */
    new Chart(document.getElementById('tasksChart'), {
        type: 'pie',
        data: {
            labels: Object.keys(tasksData),
            datasets: [{
                data: Object.values(tasksData),
                backgroundColor: ['#0f766e', '#6366f1', '#38bdf8', '#dc2626']
            }]
        },
        options: {
            plugins: { legend: { position: 'right' } }
        }
    });

    const equipeFilter = document.getElementById('equipeFilter');
    if (equipeFilter) {
        equipeFilter.value = @json($defaultEquipeId);
        equipeFilter.addEventListener('change', function () {
            const panels = document.querySelectorAll('.team-log-panel');
            panels.forEach((panel) => {
                panel.classList.toggle('hidden', panel.dataset.equipe !== this.value);
            });
        });
    }
</script>

@endsection
