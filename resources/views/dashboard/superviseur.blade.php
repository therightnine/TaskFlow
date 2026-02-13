@extends('layouts.superviseur_layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@php
    $projectCount = count($monthlyProjectProgress ?? []);
    $completedTotal = array_sum($tasksCompletedByMonth ?? []);
    $inProgressTotal = array_sum($tasksInProgressByMonth ?? []);
    $pendingTotal = array_sum($tasksPendingByMonth ?? []);
    $totalTaskFlow = $completedTotal + $inProgressTotal + $pendingTotal;
    $completionRate = $totalTaskFlow > 0 ? round(($completedTotal / $totalTaskFlow) * 100) : 0;

    $monthLabels = ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'];
    $focusMonth = (int) now()->month;
    $focusRows = collect($taskProgressByMonth[$focusMonth] ?? []);
    $focusTotal = (int) $focusRows->sum('total_tasks');
    $focusDone = (int) $focusRows->sum('completed_tasks');
    $focusRate = $focusTotal > 0 ? round(($focusDone / $focusTotal) * 100) : 0;

    $topProjects = collect($monthlyProjectProgress ?? [])
        ->map(function ($project) {
            return [
                'project_name' => $project['project_name'] ?? 'Projet',
                'done_total' => array_sum($project['monthly_completed'] ?? []),
            ];
        })
        ->sortByDesc('done_total')
        ->values()
        ->take(6);
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
        <div class="bg-rose-100 rounded-2xl p-5 cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:ring-2 hover:ring-rose-200 dashboard-stat-card" data-target="projectProgressCard">
            <div class="w-10 h-10 rounded-full bg-rose-500 flex items-center justify-center mb-4">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7 13h3v5H7zm5-4h3v9h-3zm5-6h3v15h-3z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold">{{ $projectCount }}</h3>
            <p class="text-sm text-slate-600 mt-1">Projets actifs</p>
            <p class="text-xs text-blue-600 mt-2">Portefeuille supervise actuellement</p>
        </div>

        <div class="bg-amber-100 rounded-2xl p-5 cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:ring-2 hover:ring-amber-200 dashboard-stat-card" data-target="tasksDistributionCard">
            <div class="w-10 h-10 rounded-full bg-amber-400 flex items-center justify-center mb-4">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h6m3-5H6a2 2 0 00-2 2v14a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold">{{ $totalTaskFlow }}</h3>
            <p class="text-sm text-slate-600 mt-1">Taches totales</p>
            <p class="text-xs text-blue-600 mt-2">{{ $inProgressTotal }} en cours</p>
        </div>

        <div class="bg-emerald-100 rounded-2xl p-5 cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:ring-2 hover:ring-emerald-200 dashboard-stat-card" data-target="monthPulseCard">
            <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center mb-4">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-xl font-bold">{{ $completedTotal }}</h3>
            <p class="text-sm text-slate-600 mt-1">Taches terminees</p>
            <p class="text-xs text-blue-600 mt-2">{{ $completionRate }}% completion</p>
        </div>

        <div class="bg-violet-100 rounded-2xl p-5 cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:ring-2 hover:ring-violet-200 dashboard-stat-card" data-target="tasksDistributionCard">
            <div class="w-10 h-10 rounded-full bg-violet-500 flex items-center justify-center mb-4">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-xl font-bold">{{ $pendingTotal }}</h3>
            <p class="text-sm text-slate-600 mt-1">Backlog</p>
            <p class="text-xs text-blue-600 mt-2">A traiter en priorite</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-stretch">
        <div id="projectProgressCard" class="xl:col-span-2 bg-white rounded-2xl p-6 shadow h-[390px] flex flex-col transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Progression Projets</h2>
                    <p class="text-sm text-slate-500">Comparaison mensuelle des taches terminees par projet</p>
                </div>
                <span class="text-xs px-3 py-1 rounded-full bg-cyan-50 text-cyan-700 border border-cyan-100">Line Trend</span>
            </div>
            <div class="flex-1 min-h-0">
                <canvas id="projectProgressChart"></canvas>
            </div>
        </div>

        <div id="monthPulseCard" class="bg-white rounded-2xl p-6 shadow h-[390px] flex flex-col transition-all duration-300">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Monthly Pulse</h2>
                    <p class="text-sm text-slate-500">Lecture rapide par mois et par projet</p>
                </div>
                <select id="monthFilter" class="text-sm border border-cyan-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    @foreach($monthLabels as $idx => $label)
                        <option value="{{ $idx + 1 }}" {{ ($idx + 1) === $focusMonth ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                    <p class="text-xs text-slate-500">Total mois</p>
                    <p id="pulseTotal" class="text-xl font-bold text-slate-900">{{ $focusTotal }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                    <p class="text-xs text-slate-500">Terminees mois</p>
                    <p id="pulseDone" class="text-xl font-bold text-emerald-600">{{ $focusDone }}</p>
                </div>
            </div>

            <div class="mb-3">
                <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                    <span>Efficacite mensuelle</span>
                    <span id="pulseRateLabel">{{ $focusRate }}%</span>
                </div>
                <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                    <div id="pulseRateBar" class="h-full bg-cyan-500" style="width: {{ $focusRate }}%"></div>
                </div>
            </div>

            <div id="monthProjectList" class="flex-1 min-h-0 overflow-y-auto pr-1 space-y-2">
                @forelse($focusRows as $row)
                    @php
                        $rowTotal = (int) ($row['total_tasks'] ?? 0);
                        $rowDone = (int) ($row['completed_tasks'] ?? 0);
                        $rowRate = $rowTotal > 0 ? round(($rowDone / $rowTotal) * 100) : 0;
                    @endphp
                    <div class="p-3 rounded-xl border border-slate-100 hover:border-cyan-200 transition-colors">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-medium text-slate-700">{{ $row['project_name'] }}</span>
                            <span class="text-slate-500">{{ $rowDone }}/{{ $rowTotal }}</span>
                        </div>
                        <div class="mt-2 h-1.5 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full bg-cyan-500" style="width: {{ $rowRate }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex items-center justify-center text-sm text-slate-400">Aucune donnee pour ce mois.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-stretch">
        <div id="tasksDistributionCard" class="xl:col-span-2 bg-white rounded-2xl p-6 shadow h-[390px] flex flex-col transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Distribution Des Taches</h2>
                    <p class="text-sm text-slate-500">Terminees, en cours et en attente par mois</p>
                </div>
                <span class="text-xs px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">Stacked Bars</span>
            </div>
            <div class="flex-1 min-h-0">
                <canvas id="tasksProgressChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow h-[390px] flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-slate-900">Top Projets</h2>
                <span class="text-xs px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">Impact</span>
            </div>

            <div class="flex-1 min-h-0 overflow-y-auto space-y-3 pr-1">
                @forelse($topProjects as $index => $project)
                    @php
                        $topRate = $completedTotal > 0 ? round(($project['done_total'] / $completedTotal) * 100) : 0;
                    @endphp
                    <div class="p-3 rounded-xl border border-slate-100 hover:border-emerald-200 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-slate-100 text-xs text-slate-700 flex items-center justify-center">{{ $index + 1 }}</span>
                                <p class="text-sm font-medium text-slate-800">{{ $project['project_name'] }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full bg-emerald-50 text-emerald-700">{{ $project['done_total'] }} done</span>
                        </div>
                        <div class="mt-2 h-1.5 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full bg-emerald-500" style="width: {{ $topRate }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex items-center justify-center text-sm text-slate-400">Aucun projet disponible.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const monthLabels = @json($monthLabels);
const monthlyTaskProgress = @json($taskProgressByMonth);

const colorPalette = ['#06b6d4', '#22c55e', '#6366f1', '#f97316', '#ef4444', '#14b8a6', '#8b5cf6', '#eab308'];
const projectProgressDatasets = [
@foreach($monthlyProjectProgress as $idx => $proj)
{
    label: @json($proj['project_name']),
    data: @json(array_values($proj['monthly_completed'])),
    borderColor: colorPalette[{{ $idx }} % colorPalette.length],
    backgroundColor: 'transparent',
    tension: 0.35,
    borderWidth: 2
},
@endforeach
];

new Chart(document.getElementById('projectProgressChart'), {
    type: 'line',
    data: {
        labels: monthLabels,
        datasets: projectProgressDatasets
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: '#e2e8f0' } },
            x: { grid: { display: false } }
        }
    }
});

new Chart(document.getElementById('tasksProgressChart'), {
    type: 'bar',
    data: {
        labels: monthLabels,
        datasets: [
            { label: 'Terminees', data: @json($tasksCompletedByMonth), backgroundColor: '#10b981', borderRadius: 6 },
            { label: 'En cours', data: @json($tasksInProgressByMonth), backgroundColor: '#06b6d4', borderRadius: 6 },
            { label: 'En attente', data: @json($tasksPendingByMonth), backgroundColor: '#f97316', borderRadius: 6 }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 14 } } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#e2e8f0' } },
            x: { grid: { display: false } }
        }
    }
});

const monthFilter = document.getElementById('monthFilter');
const pulseTotal = document.getElementById('pulseTotal');
const pulseDone = document.getElementById('pulseDone');
const pulseRateLabel = document.getElementById('pulseRateLabel');
const pulseRateBar = document.getElementById('pulseRateBar');
const monthProjectList = document.getElementById('monthProjectList');

function renderMonthPulse(month) {
    const rows = monthlyTaskProgress[month] || [];
    const total = rows.reduce((sum, row) => sum + Number(row.total_tasks || 0), 0);
    const done = rows.reduce((sum, row) => sum + Number(row.completed_tasks || 0), 0);
    const rate = total > 0 ? Math.round((done / total) * 100) : 0;

    pulseTotal.textContent = total;
    pulseDone.textContent = done;
    pulseRateLabel.textContent = `${rate}%`;
    pulseRateBar.style.width = `${rate}%`;

    if (!rows.length) {
        monthProjectList.innerHTML = '<div class="h-full flex items-center justify-center text-sm text-slate-400">Aucune donnee pour ce mois.</div>';
        return;
    }

    monthProjectList.innerHTML = rows.map((row) => {
        const totalTasks = Number(row.total_tasks || 0);
        const completedTasks = Number(row.completed_tasks || 0);
        const progress = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;

        return `
            <div class="p-3 rounded-xl border border-slate-100 hover:border-cyan-200 transition-colors">
                <div class="flex items-center justify-between text-sm">
                    <span class="font-medium text-slate-700">${row.project_name || 'Projet'}</span>
                    <span class="text-slate-500">${completedTasks}/${totalTasks}</span>
                </div>
                <div class="mt-2 h-1.5 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full bg-cyan-500" style="width: ${progress}%"></div>
                </div>
            </div>
        `;
    }).join('');
}

if (monthFilter) {
    monthFilter.addEventListener('change', (event) => {
        renderMonthPulse(Number(event.target.value));
    });
}

document.querySelectorAll('.dashboard-stat-card').forEach((card) => {
    card.addEventListener('click', () => {
        const targetId = card.dataset.target;
        const target = targetId ? document.getElementById(targetId) : null;
        if (!target) return;

        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        target.classList.add('ring-2', 'ring-cyan-300');
        setTimeout(() => target.classList.remove('ring-2', 'ring-cyan-300'), 900);
    });
});
</script>

@endsection
