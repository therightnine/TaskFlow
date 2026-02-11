@extends('layouts.app')

@section('title', 'Home - TaskFlow')

@section('content')

<!-- HERO SECTION -->
<!-- HERO SECTION -->
<section class="relative py-24 px-[120px] overflow-hidden bg-white">

    <!-- Decorative blur -->
    <div class="absolute -top-32 -left-32 w-[360px] h-[360px] bg-cyan-400/20 rounded-full blur-[120px]"></div>

    <div class="relative z-10 max-w-[720px]">
        <h1 class="text-[56px] font-medium leading-[72px] text-black animate-bounce">
            Planifiez Mieux, <br>
            Avancez Plus Vite <br>
            Avec <span class="text-cyan-500 ">TaskFlow</span>.
        </h1>

        <p class="mt-6 text-[18px] leading-[28px] text-gray-600 max-w-[640px]">
            Votre espace pour créer, planifier et livrer.
            <br> Une plateforme. Tous vos projets.
        </p>

        <div class="mt-10 flex gap-6">
            <a href="{{ route('register') }}"
               class="bg-cyan-500 text-white px-10 py-4 rounded-xl text-[16px]
                      transition hover:bg-white hover:text-cyan-500 hover:border hover:border-cyan-500">
                Commencer
            </a>

            <a href="{{ route('login') }}"
               class="border border-black px-10 py-4 rounded-xl text-[16px]
                      transition hover:bg-black hover:text-white">
                Connexion
            </a>
        </div>
    </div>

    <!-- Hero Image -->
    <div class="absolute right-[120px] top-[140px] w-[420px] h-[420px] rounded-xl overflow-hidden">
        <img src="{{ asset('images/hero.png') }}"
             alt="Hero Image"
             class="w-full h-full object-cover hover-scale-105 transition duration-500">
    </div>
</section>



<!-- SHOWCASE IMAGE -->
<section class="py-20 px-8 bg-white flex justify-center">
    <div class="max-w-6xl rounded-3xl overflow-hidden shadow-md
                hover:shadow-xl transition duration-500">
        <img src="{{ asset('images/features.png') }}"
             alt="Fonctionnalités"
             class="w-full h-full object-cover">
    </div>
    
</section>

 <section class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
    
            <div class="space-y-6 animate-fade-in">
                <h2 class="text-5xl font-bold leading-tight text-gray-900">
                    Construisons des expériences digitales modernes
                </h2>

                <p class="text-lg text-gray-600 leading-relaxed">
                    Nous sommes une équipe passionnée par l'innovation et la technologie.
                    Notre mission est de créer des solutions performantes,
                    intuitives et centrées sur l'utilisateur.
                </p>

                <div class="pt-4">
                    <button class="px-8 py-3 bg-cyan-600 text-white font-semibold rounded-full shadow-md 
                                   hover:bg-cyan-700 transition duration-300 transform hover:scale-105">
                        Découvrir davantage
                    </button>
                </div>
            </div>

            <!-- Image -->
            <div class="flex justify-center animate-fade-in">
                <img src="https://images.unsplash.com/photo-1551434678-e076c223a692"
                     alt="Équipe au travail"
                     class="rounded-3xl shadow-xl w-full max-w-md object-cover">
            </div>
        </section>
</section>




<!-- FEATURES SECTION -->
<section class="relative px-[120px] py-[120px] bg-white">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-[40px]">

        <!-- Card 1 -->
        <div class="group p-10 border rounded-2xl
                    transition-all duration-300
                    hover:-translate-y-2 hover:shadow-xl">
            <div class="w-12 h-12 mb-6">
                <img src="{{ asset('images/folder-star.png') }}" alt="Gestion">
            </div>
            <h3 class="text-xl font-medium mb-3">
                Gestion Simple
            </h3>
            <p class="text-gray-600 leading-relaxed">
                Organisez vos tâches et projets sans effort,
                avec une interface claire et intuitive.
            </p>
        </div>

        <!-- Card 2 -->
        <div class="group p-10 border rounded-2xl
                    transition-all duration-300
                    hover:-translate-y-2 hover:shadow-xl">
            <div class="w-12 h-12 mb-6">
                <img src="{{ asset('images/users-group.png') }}" alt="Collaboration">
            </div>
            <h3 class="text-xl font-medium mb-3">
                Collaboration Fluide
            </h3>
            <p class="text-gray-600 leading-relaxed">
                Travaillez en équipe, partagez l’avancement
                et restez synchronisés en temps réel.
            </p>
        </div>

        <!-- Card 3 -->
        <div class="group p-10 border rounded-2xl
                    transition-all duration-300
                    hover:-translate-y-2 hover:shadow-xl">
            <div class="w-12 h-12 mb-6">
                <img src="{{ asset('images/layout-dashboard.png') }}" alt="Suivi">
            </div>
            <h3 class="text-xl font-medium mb-3">
                Suivi Intelligent
            </h3>
            <p class="text-gray-600 leading-relaxed">
                Visualisez l’état de vos projets et prenez
                les bonnes décisions au bon moment.
            </p>
        </div>
    </div>
</section>

@endsection
