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
                    ['route' => 'dashboard.contributeur', 'icon' => 'ic_dashboard.png', 'label' => 'Tableau de bord'],
                    ['route' => 'projects.index', 'icon' => 'ic_projects.png', 'label' => 'Projets'],
                    ['route' => 'tasks.index', 'icon' => 'ic_tasks.png', 'label' => 'Taches'],
                    ['route' => 'equipe', 'icon' => 'ic_teams.png', 'label' => 'Equipes'],
                    ['route' => 'settings', 'icon' => 'ic_settings.png', 'label' => 'Paramétres'],
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
                             class="w-6 h-6 transition-all"
                             style="{{ $active ? 'filter: brightness(0) invert(1);' : '' }}">
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
                        Déconnexion
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
                @yield('page-title', 'Projets')
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
                    @if($notifications->count())
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    @endif
                </button>

                <div id="notifDropdownMenu"
                    class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden max-h-96 overflow-y-auto">
                    <div class="p-4 border-b border-gray-100 font-semibold text-gray-700">
                        Activité récente
                    </div>
                    <ul>
                        @forelse ($notifications as $notification)
                            <li class="flex items-start gap-3 p-3 hover:bg-gray-50 transition-colors">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-100 to-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $notification['title'] }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $notification['message'] }}</p>
                                </div>
                                <span class="text-xs text-gray-400 flex-shrink-0">
                                    {{ $notification['time']->diffForHumans() }}
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
                            <p class="text-xs text-gray-400">Contributeur</p>
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
                                    Gérer Compte
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile') }}"
                                   class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <img src="{{ asset('images/ic_showprofile.png') }}" class="w-5 h-5">
                                    Voir Profil
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 px-4 py-2 w-full text-gray-700 hover:bg-gray-100">
                                        <img src="{{ asset('images/ic_logout.png') }}" class="w-5 h-5">
                                        Déconnexion
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

        <script>
            const notifBtn = document.getElementById('notifDropdownBtn');
            const notifMenu = document.getElementById('notifDropdownMenu');

            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notifMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!notifBtn.contains(e.target) && !notifMenu.contains(e.target)) {
                    notifMenu.classList.add('hidden');
                }
            });
        </script>

        {{-- CONTENT --}}
        <main class="flex-1 p-6 overflow-y-auto">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>
