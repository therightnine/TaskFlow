@extends('layouts.app')

@section('title', 'À propos')

@section('content')

<div class="bg-white">

    {{-- HERO INTRO --}}
    <section class="py-24 px-6 text-center">
        <div class="max-w-3xl mx-auto">

            <h2 class="text-4xl md:text-5xl font-medium text-black mb-6 animate-bounce">
                Bienvenue dans l’univers <span class="text-cyan-500">TaskFlow</span>
            </h2>

            <p class="text-gray-600 text-lg leading-relaxed">
                Nous sommes une équipe passionnée par l'innovation et la technologie.
                Notre mission est de créer des expériences numériques simples,
                puissantes et intuitives.
            </p>

            <div class="mt-10">
                <a href="#vision"
                   class="inline-block px-8 py-3 rounded-xl bg-cyan-500 text-white font-semibold
                          transition-all duration-300
                          hover:bg-white hover:text-cyan-500 hover:border hover:border-cyan-500
                          hover:-translate-y-1 hover:shadow-lg">
                    Découvrir notre vision
                </a>
            </div>

        </div>
    </section>

    


     <!-- ÉQUIPE SECTION (Gradient only here) -->
        <section class="relative py-20 px-6 rounded-3xl overflow-hidden 
                        bg-gradient-to-r from-cyan-500 to-cyan-300 text-white">

            <div class="relative z-10 max-w-7xl mx-auto">
                <h3 class="text-4xl font-bold text-center mb-14 animate-fade-in">
                    Notre Équipe
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                    <!-- Member -->
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl text-center
                                transition transform hover:scale-105 hover:bg-white/20 shadow-lg">
                        <img src="https://randomuser.me/api/portraits/women/43.jpg"
                             alt="Sirine Hfaidh"
                             class="w-28 h-28 rounded-full mx-auto mb-4 shadow-md">
                        <h4 class="text-xl font-bold">Sirine Hfaidh</h4>
                        <p class="text-cyan-100 mt-2">Développeur Full-Stack</p>
                        <p class="mt-3 text-sm text-gray-200">
                            Création d'interfaces modernes et développement backend robuste.
                        </p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl text-center
                                transition transform hover:scale-105 hover:bg-white/20 shadow-lg">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg"
                             alt="Khaoula Chebil"
                             class="w-28 h-28 rounded-full mx-auto mb-4 shadow-md">
                        <h4 class="text-xl font-bold">Khaoula Chebil</h4>
                        <p class="text-cyan-100 mt-2">Développeur Full-Stack</p>
                        <p class="mt-3 text-sm text-gray-200">
                            Solutions performantes et optimisation technique.
                        </p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl text-center
                                transition transform hover:scale-105 hover:bg-white/20 shadow-lg">
                        <img src="https://randomuser.me/api/portraits/women/68.jpg"
                             alt="Ahlem Hmida"
                             class="w-28 h-28 rounded-full mx-auto mb-4 shadow-md">
                        <h4 class="text-xl font-bold">Ahlem Hmida</h4>
                        <p class="text-cyan-100 mt-2">Développeur Full-Stack</p>
                        <p class="mt-3 text-sm text-gray-200">
                            Supervision de projets et gestion technique.
                        </p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl text-center
                                transition transform hover:scale-105 hover:bg-white/20 shadow-lg">
                        <img src="https://randomuser.me/api/portraits/women/50.jpg"
                             alt="Olfa Mejri"
                             class="w-28 h-28 rounded-full mx-auto mb-4 shadow-md">
                        <h4 class="text-xl font-bold">Olfa Mejri</h4>
                        <p class="text-cyan-100 mt-2">Développeur Full-Stack</p>
                        <p class="mt-3 text-sm text-gray-200">
                            Qualité, performance et innovation continue.
                        </p>
                    </div>

                </div>
            </div>
        </section>

    {{-- VISION SECTION --}}
    <section id="vision" class="py-24 px-6">
        <div class="max-w-4xl mx-auto text-center">

            <h3 class="text-3xl font-medium text-black mb-8">
                Notre Vision
            </h3>

            <p class="text-gray-600 text-lg leading-relaxed mb-6">
                Nous croyons que la technologie doit simplifier la gestion
                et améliorer la collaboration.
                Chaque fonctionnalité est pensée pour offrir clarté et efficacité.
            </p>

            <p class="text-gray-600 text-lg leading-relaxed">
                TaskFlow évolue constamment grâce à l’innovation,
                à l’écoute de nos utilisateurs et à notre passion
                pour l’excellence produit.
            </p>

        </div>
    </section>

</div>

@endsection
