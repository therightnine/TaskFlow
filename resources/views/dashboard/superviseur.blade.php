@extends('layouts.superviseur_layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="bg-gray-50 rounded-3xl p-6 space-y-8 min-h-[calc(100vh-4rem)] flex flex-col">

    {{-- TOP STATS --}}
<div class="grid grid-cols-1 xl:grid-cols-4 gap-6">

    {{-- Projets actifs --}}
    <div class="bg-rose-100 rounded-2xl p-5">
        <div class="w-10 h-10 rounded-full bg-rose-500 flex items-center justify-center mb-4">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 3v18h18M7 13h3v5H7zm5-4h3v9h-3zm5-6h3v15h-3z" />
            </svg>
        </div>
        <h3 class="text-xl font-bold">{{ count($monthlyProjectProgress) }}</h3>
        <p class="text-sm text-slate-600 mt-1">Projets actifs</p>
        <p class="text-xs text-blue-600 mt-2">+{{ count($monthlyProjectProgress) - 1 }} depuis la semaine dernière</p>
    </div>

    {{-- Tâches totales --}}
    <div class="bg-amber-100 rounded-2xl p-5">
        <div class="w-10 h-10 rounded-full bg-amber-400 flex items-center justify-center mb-4">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12h6m-6 4h6M9 8h6m3-5H6a2 2 0 00-2 2v14a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2z" />
            </svg>
        </div>
        <h3 class="text-xl font-bold">{{ collect($taskProgressByMonth)->flatten(1)->sum('total_tasks') }}</h3>
        <p class="text-sm text-slate-600 mt-1">Tâches totales</p>
        <p class="text-xs text-blue-600 mt-2">+5 par rapport à hier</p>
    </div>

    {{-- Mois suivis --}}
    <div class="bg-emerald-100 rounded-2xl p-5">
        <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center mb-4">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h3 class="text-xl font-bold">{{ count($taskProgressByMonth) }}</h3>
        <p class="text-sm text-slate-600 mt-1">Mois suivis</p>
        <p class="text-xs text-blue-600 mt-2">6 mois suivis</p>
    </div>

    {{-- Performance --}}
    <div class="bg-violet-100 rounded-2xl p-5">
        <div class="w-10 h-10 rounded-full bg-violet-500 flex items-center justify-center mb-4">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h3 class="text-xl font-bold">✔️</h3>
        <p class="text-sm text-slate-600 mt-1">Performance</p>
        <p class="text-xs text-blue-600 mt-2">Excellente</p>
    </div>

</div>


    {{-- MAIN CONTENT --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 flex-1 items-stretch">

        {{-- Avancement Projets --}}
        <div class="bg-white rounded-xl p-6 shadow-sm xl:col-span-2 h-full flex flex-col">
            <div class="mb-4">
                <h2 class="text-lg font-semibold">Avancement Projets</h2>
                <p class="text-sm text-gray-500">
                    Total de projets assignés : {{ count($monthlyProjectProgress) }}
                </p>
            </div>

            <div class="flex-1">
                <canvas id="projectProgressChart" class="w-full h-full"></canvas>
            </div>
        </div>

        {{-- Avancement Tâches --}}
        <div class="bg-white rounded-xl p-6 shadow-sm h-full flex flex-col">
            <div class="mb-4">
                <h2 class="font-semibold text-lg text-slate-900">Avancement Tâches</h2>
                <p class="text-sm text-slate-400">This Week</p>
            </div>

            <div class="flex-1">
                <canvas id="tasksProgressChart" class="w-full h-full"></canvas>
            </div>
        </div>

    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('projectProgressChart').getContext('2d');
const datasets = [
@foreach($monthlyProjectProgress as $proj)
{
    label: '{{ $proj['project_name'] }}',
    data: {!! json_encode(array_values($proj['monthly_completed'])) !!},
    borderColor: '{{ 'hsl(' . rand(0,360) . ', 70%, 50%)' }}',
    backgroundColor: 'transparent',
    tension: 0.35
},
@endforeach
];

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
    }
});

const tasksCtx = document.getElementById('tasksProgressChart').getContext('2d');
new Chart(tasksCtx, {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [
            { label: 'Terminées', data: @json($tasksCompletedByMonth), backgroundColor: '#6366F1', borderRadius: 6 },
            { label: 'En cours', data: @json($tasksInProgressByMonth), backgroundColor: '#22C55E', borderRadius: 6 },
            { label: 'En attente', data: @json($tasksPendingByMonth), backgroundColor: '#F97316', borderRadius: 6 }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 16 } } },
        scales: { y: { beginAtZero: true, grid: { color: '#F1F5F9' } }, x: { grid: { display: false } } }
    }
});
</script>

@endsection
