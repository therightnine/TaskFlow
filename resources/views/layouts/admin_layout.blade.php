<!DOCTYPE html>
<html lang="fr">
@php
    $user = auth()->user();
@endphp

<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'TaskFlow')</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600&family=Inter:wght@400;500&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#238899' },
                    fontFamily: {
                        vietnam: ['Be Vietnam Pro', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                    },
                },
            }
        }
    </script>
</head>

<body class="bg-gray-100 font-vietnam min-h-screen">

<div class="flex h-screen">

    {{-- SIDEBAR --}}
    <aside class="w-72 bg-white shadow-lg flex flex-col px-8 py-6">

        {{-- Logo --}}
        <div class="mb-12 flex justify-center">
            <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="w-40 h-auto object-contain">
        </div>

        {{-- Menu --}}
        <ul class="flex-1 space-y-4 text-gray-600 text-lg">
           @php
                $menuItems = [
                    ['route' => 'dashboard.admin', 'icon' => 'ic_dashboard.png', 'label' => 'Dashboard'],
                    ['route' => 'admin.abonnements.gest_abonnements', 'icon' => 'ic_projects.png', 'label' => 'Abonnements'],
                    ['route' => 'admin.roles.gest_roles', 'icon' => 'ic_teams.png', 'label' => 'Rôles'],
                    ['route' => 'admin.utilisateurs.index', 'icon' => 'ic_teams.png', 'label' => 'Utilisateurs'],
                    ['route' => 'settings', 'icon' => 'ic_settings.png', 'label' => 'Paramètres'],
                ];
            @endphp


            @foreach($menuItems as $item)
                @php
                    $active = $item['route'] === 'settings'
                        ? Route::is('settings') || Route::is('profile')
                        : Route::is($item['route']);
                @endphp
                <li>
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-4 py-3 px-4 rounded transition
                              {{ $active ? 'bg-primary text-white' : 'hover:text-primary' }}">
                        
                            <img src="{{ asset('images/' . $item['icon']) }}"
                            class="w-6 h-6 transition-all {{ $active ? 'brightness-0 invert' : '' }}">

                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach

            {{-- Sign Out --}}
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-4 py-3 px-4 w-full text-gray-600 hover:text-primary rounded transition">
                        <img src="{{ asset('images/ic_signout.png') }}" class="w-6 h-6 transition-all">
                        Se déconnecter
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    {{-- MAIN AREA --}}
    <div class="flex-1 flex flex-col">

        {{-- TOP MENU --}}
        <header class="h-20 bg-white shadow-sm flex items-center px-8">
            <h1 class="text-xl font-semibold text-gray-800">
                @yield('page-title', 'Interface super admin')
            </h1>

            {{-- SEARCH --}}
            <div class="flex-1 flex justify-center">
                <div class="relative w-[420px]">
                    <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                        <img src="{{ asset('images/ic_magnifier.png') }}" class="w-6 h-6">
                    </span>
                    <input type="text" placeholder="Search here..."
                           class="w-full pl-12 pr-4 py-3 rounded-full bg-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-primary" />
                </div>
            </div>

            <span class="mx-4 h-6 border-l border-gray-300"></span>

            {{-- NOTIFICATIONS --}}
            <div class="relative">
                <button id="notifDropdownBtn" class="relative cursor-pointer focus:outline-none">
                    <img src="{{ asset('images/ic_bell.png') }}" class="w-6 h-6">
                    @if($recentTasks->count())
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    @endif
                </button>

                <div id="notifDropdownMenu"
                    class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden max-h-96 overflow-y-auto">
                    <div class="p-4 border-b border-gray-100 font-semibold text-gray-700">
                        Activité récente
                    </div>
                    <ul>
                        @forelse ($recentTasks as $task)
                            <li class="flex items-start gap-3 p-3 hover:bg-gray-50 transition-colors">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-100 to-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $task->nom_tache }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Mis à jour {{ \Carbon\Carbon::parse($task->updated_at)->diffForHumans() }}</p>
                                </div>
                                <span class="text-xs text-gray-400 flex-shrink-0">
                                    {{ \Carbon\Carbon::parse($task->updated_at)->format('H:i') }}
                                </span>
                            </li>
                        @empty
                            <li class="text-center py-4 text-gray-400 text-sm">Aucune activité récente</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <span class="mx-4 h-6 border-l border-gray-300"></span>

            {{-- USER INFO --}}
            <div class="flex items-center gap-6">
                <div class="relative">
                    <button id="userDropdownBtn" class="flex items-center gap-3 cursor-pointer focus:outline-none">
                        <img src="{{ $user && $user->photo ? asset($user->photo) : asset('images/default-avatar.png') }}"
                             class="w-10 h-10 rounded-full object-cover">
                        <div class="leading-tight">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $user->prenom ?? '' }} {{ $user->nom ?? '' }}
                            </p>
                            <p class="text-xs text-gray-400">Superadmin</p>
                        </div>
                        <span class="text-gray-400">▾</span>
                    </button>

                    <div id="userDropdownMenu"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden">
                        <ul class="py-2">
                            <li>
                                <a href="{{ route('settings') }}"
                                   class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <img src="{{ asset('images/ic_manageaccount.png') }}" class="w-5 h-5">
                                    Gérer le compte
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile') }}"
                                   class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <img src="{{ asset('images/ic_showprofile.png') }}" class="w-5 h-5">
                                    Voir le profil
                                </a>
                            </li>
                           
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 px-4 py-2 w-full text-gray-700 hover:bg-gray-100">
                                        <img src="{{ asset('images/ic_logout.png') }}" class="w-5 h-5">
                                        Se déconnecter
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        {{-- Dropdown JS --}}
        <script>
            const dropdownBtn = document.getElementById('userDropdownBtn');
            const dropdownMenu = document.getElementById('userDropdownMenu');
            dropdownBtn.addEventListener('click', () => dropdownMenu.classList.toggle('hidden'));
            document.addEventListener('click', e => {
                if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        </script>

        {{-- CONTENT --}}
        <main class="flex-1 p-6 overflow-y-auto">
            @yield('content')
        </main>

    </div>
</div>

{{-- Chart.js CDN --}}
<div id="toast-root" class="fixed top-4 right-4 z-50 space-y-2"></div> <!-- Toast Container -->
<!-- debut script relié à destroy-->
<script>  
(function () {
    function getCsrfToken() {
        const el = document.querySelector('meta[name="csrf-token"]');
        return el ? el.getAttribute('content') : '';
    }

    async function deleteAbonnement(url, id, name, buttonEl) {
        const row = document.getElementById(`ab-row-${id}`);
        try {
            buttonEl.disabled = true;
            buttonEl.classList.add('opacity-50', 'cursor-not-allowed');

            const res = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                }
            });
            // 🔁 Rechargement si succès (OK, 204 ou redirection 302)
            if (res.ok || res.status === 204 || res.status === 302) {
                location.reload();
                return; // on stoppe la suite pour éviter l'animation inutile
            }
            if (!res.ok && res.status !== 204) {
                let detail = '';
                try { detail = await res.text(); } catch (e) {}
                throw new Error(detail || `Erreur HTTP ${res.status}`);
            }

            // Supprime la ligne (effet léger)
            if (row) {
                row.classList.add('transition', 'duration-200', 'opacity-0', 'scale-95');
                setTimeout(() => row.remove(), 200);
            }

            // Toast simple (optionnel)
            console.log(`Offre "${name}" supprimée.`);

        } catch (err) {
            console.error(err);
            alert(`Suppression impossible : ${err.message || 'erreur inconnue'}`);
        } finally {
            buttonEl.disabled = false;
            buttonEl.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
    async function deleteRole(url, id, name, buttonEl) {
        const row = document.getElementById(`role-row-${id}`);
        try {
            buttonEl.disabled = true;
            buttonEl.classList.add('opacity-50', 'cursor-not-allowed');

            const res = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                }
            });
            // 🔁 Rechargement si succès (OK, 204 ou redirection 302)
            if (res.ok || res.status === 204 || res.status === 302) {
                location.reload();
                return; // on stoppe la suite pour éviter l'animation inutile
            }
            if (!res.ok && res.status !== 204) {
                let detail = '';
                try { detail = await res.text(); } catch (e) {}
                throw new Error(detail || `Erreur HTTP ${res.status}`);
            }

            // Supprime la ligne (effet léger)
            if (row) {
                row.classList.add('transition', 'duration-200', 'opacity-0', 'scale-95');
                setTimeout(() => row.remove(), 200);
            }

            // Toast simple (optionnel)
            console.log(`Role "${name}" supprimée.`);

        } catch (err) {
            console.error(err);
            alert(`Suppression impossible : ${err.message || 'erreur inconnue'}`);
        } finally {
            buttonEl.disabled = false;
            buttonEl.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    // Délégation d'événement : un seul listener pour tous les boutons .btn-delete-ab
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-ab');
        if (!btn) return;

        const id = btn.dataset.id;
        const url = btn.dataset.url;
        const name = btn.dataset.name || 'cet abonnement';

        if (!url || !id) {
            alert('Données manquantes pour la suppression.');
            return;
        }

        const ok = confirm(`Voulez-vous vraiment supprimer l'abonnement "${name}" ?`);
        if (!ok) return;

        deleteAbonnement(url, id, name, btn);
        
    });
    // Délégation d'événement : un seul listener pour tous les boutons .btn-delete-role
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-role');
        if (!btn) return;
        const id = btn.dataset.id;
        const url = btn.dataset.url;
        const name = btn.dataset.name || 'ce rôle';

        if (!url || !id) {
            alert('Données manquantes pour la suppression.');
            return;
        }
        const ok = confirm(`Voulez-vous vraiment supprimer le rôle "${name}" ?`);
        if (!ok) return;

        deleteRole(url, id, name, btn);
    });
})();
</script>
<!-- fin script relié à destroy-->
</body>
</html>
