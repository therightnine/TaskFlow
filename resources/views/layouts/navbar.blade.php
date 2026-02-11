<header class="absolute top-0 left-0 w-full h-[80px] flex items-center px-[120px] bg-white z-50">

    <!-- Logo avec lien -->
    <a href="{{ route('home') }}" class="transition {{ request()->routeIs('home') ? 'text-cyan-500 font-semibold' : 'text-black hover:text-cyan-500' }}">
        <img src="{{ asset('images/Logo.png') }}" alt="Logo TaskFlow" class="h-full w-full object-contain">
    </a>

    <!-- Navigation -->
    <nav class="flex-1 flex justify-center gap-10 text-[16px]">
        <a href="{{ route('home') }}" class="text-black hover:text-cyan-500 transition {{ request()->routeIs('home') ? 'text-cyan-500 font-semibold' : '' }}">Accueil</a>
        <a href="{{ route('fonctionalite') }}" class="text-black hover:text-cyan-500 transition {{ request()->routeIs('fonctionalite') ? 'text-cyan-500 font-semibold' : '' }}">Fonctionnalités</a>
        <a href="{{ route('abonnements.index') }}" class="text-black hover:text-cyan-500 transition {{ request()->routeIs('abonnements.index') ? 'text-cyan-500 font-semibold' : '' }}">Tarifs</a>
        <a href="{{ route('about') }}" class="text-black hover:text-cyan-500 transition {{ request()->routeIs('about') ? 'text-cyan-500 font-semibold' : '' }}">À propos</a>
        <a href="{{ route('contact') }}" class="text-black hover:text-cyan-500 transition {{ request()->routeIs('contact') ? 'text-cyan-500 font-semibold' : '' }}">Contact</a>
    </nav>

    <!-- Boutons -->
    <div class="flex gap-4">
        <!-- Connexion -->
        <a href="{{ route('login') }}" 
           class="bg-cyan-500 text-white px-6 py-2 rounded-xl text-[16px] inline-block text-center
                  hover:bg-white hover:text-cyan-500 hover:border hover:border-cyan-500 transition">
            Connexion
        </a>

        <!-- Inscription -->
        <a href="{{ route('register') }}"  class="border border-cyan-500 text-cyan-500 bg-white px-6 py-2 rounded-xl text-[16px]
                       hover:bg-cyan-500 hover:text-white transition">
            Inscription
        </a>
    </div>
</header>
