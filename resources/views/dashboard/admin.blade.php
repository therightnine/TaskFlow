@extends('layouts.admin_layout')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-2xl p-6 shadow">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="font-semibold text-lg text-slate-900">Platform Overview</h2>
                    <p class="text-sm text-slate-400 mt-1">Vue globale de la plateforme</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-cyan-50 rounded-2xl p-5">
                    <div class="w-10 h-10 rounded-full bg-cyan-500 flex items-center justify-center mb-4 text-white">U</div>
                    <h3 class="text-xl font-bold">{{ $totalUsers }}</h3>
                    <p class="text-sm text-slate-600 mt-1">Utilisateurs</p>
                    <p class="text-xs text-blue-600 mt-2">{{ $totalRoles }} roles</p>
                </div>

                <div class="bg-emerald-50 rounded-2xl p-5">
                    <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center mb-4 text-white">A</div>
                    <h3 class="text-xl font-bold">{{ $activeSubscriptions }}</h3>
                    <p class="text-sm text-slate-600 mt-1">Abonnements actifs</p>
                    <p class="text-xs text-blue-600 mt-2">{{ $expiringSoon }} expirent bientot</p>
                </div>

                <div class="bg-amber-50 rounded-2xl p-5">
                    <div class="w-10 h-10 rounded-full bg-amber-500 flex items-center justify-center mb-4 text-white">P</div>
                    <h3 class="text-xl font-bold">{{ $totalProjects }}</h3>
                    <p class="text-sm text-slate-600 mt-1">Projets</p>
                    <p class="text-xs text-blue-600 mt-2">{{ $totalTasks }} taches</p>
                </div>

                <div class="bg-violet-50 rounded-2xl p-5">
                    <div class="w-10 h-10 rounded-full bg-violet-500 flex items-center justify-center mb-4 text-white">$</div>
                    <h3 class="text-xl font-bold">{{ number_format((float) $monthlyRecurringRevenue, 2) }}</h3>
                    <p class="text-sm text-slate-600 mt-1">MRR estime</p>
                    <p class="text-xs text-blue-600 mt-2">{{ $totalPlans }} plans disponibles</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow">
            <h2 class="font-semibold text-lg mb-4">Actions Rapides</h2>
            <div class="space-y-3">
                <a href="{{ route('admin.abonnements.gest_abonnements') }}" class="block rounded-xl border border-gray-200 px-4 py-3 hover:bg-gray-50 transition">Gerer les abonnements</a>
                <a href="{{ route('admin.roles.gest_roles') }}" class="block rounded-xl border border-gray-200 px-4 py-3 hover:bg-gray-50 transition">Gerer les roles</a>
                <a href="{{ route('admin.utilisateurs.index') }}" class="block rounded-xl border border-gray-200 px-4 py-3 hover:bg-gray-50 transition">Gerer les utilisateurs</a>
            </div>

            <div class="mt-6 rounded-xl bg-gray-50 p-4 border border-gray-100">
                <p class="text-xs text-gray-500">Etat abonnements</p>
                <p class="text-sm text-gray-700 mt-1">Total actifs: <strong>{{ $subscriptionTotal }}</strong></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">Activations par mois</h2>
                <span class="bg-blue-50 text-blue-600 text-sm px-3 py-1 rounded-full">6 derniers mois</span>
            </div>
            <canvas id="growthChart" height="180"></canvas>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">Repartition des plans actifs</h2>
                <span class="bg-blue-50 text-blue-600 text-sm px-3 py-1 rounded-full">Aujourd'hui</span>
            </div>
            <canvas id="plansChart" height="180"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">Utilisateurs par role</h2>
                <span class="bg-blue-50 text-blue-600 text-sm px-3 py-1 rounded-full">Snapshot</span>
            </div>
            <canvas id="rolesChart" height="180"></canvas>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">Activite recente</h2>
                <span class="bg-blue-50 text-blue-600 text-sm px-3 py-1 rounded-full">Abonnements</span>
            </div>

            <div class="space-y-3 max-h-72 overflow-y-auto pr-2">
                @forelse($recentSubscriptionActivity as $item)
                    <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-cyan-100 to-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800">{{ $item['title'] }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $item['message'] }}</p>
                        </div>
                        <span class="text-xs text-gray-400 flex-shrink-0">{{ $item['time']->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Aucune activite recente</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    const monthLabels = @json($monthLabels ?? []);
    const monthlySubscriptions = @json($monthlySubscriptions ?? []);
    const monthlyUserRegistrations = @json($monthlyUserRegistrations ?? []);
    const subscriptionLabels = @json($subscriptionLabels ?? []);
    const subscriptionValues = @json($subscriptionValues ?? []);
    const roleLabels = @json($roleLabels ?? []);
    const roleValues = @json($roleValues ?? []);

    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [
                {
                    label: 'Nouveaux abonnements',
                    data: monthlySubscriptions,
                    borderColor: '#238899',
                    backgroundColor: 'rgba(35,136,153,0.12)',
                    tension: 0.35,
                    fill: true
                },
                {
                    label: 'Nouveaux utilisateurs',
                    data: monthlyUserRegistrations,
                    borderColor: '#6366f1',
                    tension: 0.35
                }
            ]
        },
        options: {
            plugins: { legend: { position: 'bottom' } },
            responsive: true
        }
    });

    new Chart(document.getElementById('plansChart'), {
        type: 'doughnut',
        data: {
            labels: subscriptionLabels.length ? subscriptionLabels : ['Aucune donnee'],
            datasets: [{
                data: subscriptionValues.length ? subscriptionValues : [1],
                backgroundColor: ['#06b6d4','#8b5cf6','#f59e0b','#10b981','#ef4444','#3b82f6','#a3e635','#f97316']
            }]
        },
        options: {
            plugins: { legend: { position: 'right' } },
            cutout: '60%'
        }
    });

    new Chart(document.getElementById('rolesChart'), {
        type: 'bar',
        data: {
            labels: roleLabels.length ? roleLabels : ['Aucun role'],
            datasets: [{
                label: 'Utilisateurs',
                data: roleValues.length ? roleValues : [0],
                backgroundColor: ['#238899', '#60a5fa', '#8b5cf6', '#f59e0b', '#10b981']
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection
