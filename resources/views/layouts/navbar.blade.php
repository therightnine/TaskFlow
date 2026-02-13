<header x-data="{ open: false }" class="fixed top-0 left-0 w-full h-[80px] flex items-center px-4 md:px-[120px] bg-white z-50 border-b border-gray-100">
    <a href="{{ route('home') }}" class="shrink-0 transition {{ request()->routeIs('home') ? 'text-cyan-500 font-semibold' : 'text-black hover:text-cyan-500' }}">
        <img src="{{ asset('images/Logo.png') }}" alt="Logo TaskFlow" class="h-10 w-auto object-contain">
    </a>

    <nav class="hidden md:flex flex-1 justify-center gap-8 text-[16px]">
        <a href="{{ route('home') }}" class="text-black hover:text-cyan-500 transition {{ request()->routeIs('home') ? 'text-cyan-500 font-semibold' : '' }}">Accueil</a>
        <a href="{{ route('fonctionalite') }}" class="text-black hover:text-cyan-500 transition {{ request()->routeIs('fonctionalite') ? 'text-cyan-500 font-semibold' : '' }}">Fonctionnalites</a>
        <a href="{{ route('abonnements.index') }}" class="text-black hover:text-cyan-500 transition {{ request()->routeIs('abonnements.index') ? 'text-cyan-500 font-semibold' : '' }}">Tarifs</a>
        <a href="{{ route('about') }}" class="text-black hover:text-cyan-500 transition {{ request()->routeIs('about') ? 'text-cyan-500 font-semibold' : '' }}">A propos</a>
        <a href="{{ route('contact') }}" class="text-black hover:text-cyan-500 transition {{ request()->routeIs('contact') ? 'text-cyan-500 font-semibold' : '' }}">Contact</a>
    </nav>

    <div class="hidden md:flex gap-3">
        <a href="{{ route('login') }}"
           class="bg-cyan-500 text-white px-6 py-2 rounded-xl text-[16px] inline-block text-center hover:bg-white hover:text-cyan-500 hover:border hover:border-cyan-500 transition">
            Connexion
        </a>
        <a href="{{ route('register') }}"
           class="border border-cyan-500 text-cyan-500 bg-white px-6 py-2 rounded-xl text-[16px] hover:bg-cyan-500 hover:text-white transition">
            Inscription
        </a>
    </div>

    <button @click="open = !open" class="ml-auto md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-200 text-gray-700">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <div x-show="open" x-transition class="absolute top-[80px] left-0 w-full bg-white border-b border-gray-200 shadow-lg md:hidden">
        <nav class="px-4 py-4 space-y-1 text-sm">
            <a @click="open=false" href="{{ route('home') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-50 {{ request()->routeIs('home') ? 'text-cyan-600 font-semibold' : 'text-gray-700' }}">Accueil</a>
            <a @click="open=false" href="{{ route('fonctionalite') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-50 {{ request()->routeIs('fonctionalite') ? 'text-cyan-600 font-semibold' : 'text-gray-700' }}">Fonctionnalites</a>
            <a @click="open=false" href="{{ route('abonnements.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-50 {{ request()->routeIs('abonnements.index') ? 'text-cyan-600 font-semibold' : 'text-gray-700' }}">Tarifs</a>
            <a @click="open=false" href="{{ route('about') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-50 {{ request()->routeIs('about') ? 'text-cyan-600 font-semibold' : 'text-gray-700' }}">A propos</a>
            <a @click="open=false" href="{{ route('contact') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-50 {{ request()->routeIs('contact') ? 'text-cyan-600 font-semibold' : 'text-gray-700' }}">Contact</a>
        </nav>
        <div class="px-4 pb-4 grid grid-cols-2 gap-3">
            <a @click="open=false" href="{{ route('login') }}" class="text-center bg-cyan-500 text-white px-4 py-2 rounded-lg">Connexion</a>
            <a @click="open=false" href="{{ route('register') }}" class="text-center border border-cyan-500 text-cyan-600 px-4 py-2 rounded-lg">Inscription</a>
        </div>
    </div>
</header>
