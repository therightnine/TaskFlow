@extends('layouts.app')

@section('title', 'À propos')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-white via-cyan-500 to-zinc-900 text-gray-100 flex flex-col">
    

    <!-- Main Content -->
    <main class="flex-1 p-10 flex flex-col items-center space-y-12">
        <!-- Intro -->
        <section class="text-center max-w-3xl space-y-6">
            <h2 class="text-5xl font-bold animate-bounce text-cyan-600">Bienvenue Dans Notre Univers</h2>
            <p class="text-gray-800 text-lg leading-relaxed">
                Nous sommes une équipe passionnée par l'innovation, la technologie et la créativité. 
                Notre mission est de construire des expériences numériques fluides et engageantes, 
                tout en favorisant la collaboration et la croissance personnelle de chaque membre de notre équipe.
            </p>
            <p class="text-gray-800 text-lg leading-relaxed">
                Depuis notre création, nous avons travaillé sur des projets qui connectent les gens, simplifient les processus et inspirent l'innovation. 
                Chaque défi est une opportunité de créer quelque chose d'exceptionnel.
            </p>
            <button class="px-8 py-3 bg-white text-cyan-700 font-semibold rounded-full hover:bg-cyan-50 transition-all duration-300 shadow-lg transform hover:scale-105">
                En savoir plus
            </button>
        </section>

        <!-- Équipe Section -->
        <section class="w-full max-w-7xl">
            <h3 class="text-4xl font-semibold text-center mb-10 ">Notre Équipe</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Member 1 -->
                <div class="bg-white bg-opacity-10 backdrop-blur-md p-6 rounded-2xl flex flex-col items-center transition transform hover:scale-105 hover:bg-opacity-20 shadow-lg">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Sirine Hfaidh" class="w-32 h-32 rounded-full mb-4 shadow-md">
                    <h4 class="text-xl font-bold text-gray-100">Sirine Hfaidh</h4>
                    <p class="text-cyan-200 mt-2">Développeur Full-Stack</p>
                    <p class="mt-3 text-center text-sm text-gray-300">
                        Passionnée par le design et l'expérience utilisateur, Sirine crée des interfaces élégantes en combinant backend et frontend.
                    </p>
                </div>

                <!-- Member 2 -->
                <div class="bg-white bg-opacity-10 backdrop-blur-md p-6 rounded-2xl flex flex-col items-center transition transform hover:scale-105 hover:bg-opacity-20 shadow-lg">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Khaoula Chebil" class="w-32 h-32 rounded-full mb-4 shadow-md">
                    <h4 class="text-xl font-bold text-gray-100">Khaoula Chebil</h4>
                    <p class="text-cyan-200 mt-2">Développeur Full-Stack</p>
                    <p class="mt-3 text-center text-sm text-gray-300">
                        Khaoula développe des solutions robustes et performantes, en combinant backend et frontend avec passion.
                    </p>
                </div>

                <!-- Member 3 -->
                <div class="bg-white bg-opacity-10 backdrop-blur-md p-6 rounded-2xl flex flex-col items-center transition transform hover:scale-105 hover:bg-opacity-20 shadow-lg">
                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Ahlem Hmida" class="w-32 h-32 rounded-full mb-4 shadow-md">
                    <h4 class="text-xl font-bold text-gray-100">Ahlem Hmida</h4>
                    <p class="text-cyan-200 mt-2">Développeur Full-Stack</p>
                    <p class="mt-3 text-center text-sm text-gray-300">
                        Ahlem supervise les projets avec expertise et s'assure que chaque initiative est livrée avec succès.
                    </p>
                </div>

                <!-- Member 4 -->
                <div class="bg-white bg-opacity-10 backdrop-blur-md p-6 rounded-2xl flex flex-col items-center transition transform hover:scale-105 hover:bg-opacity-20 shadow-lg">
                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Olfa Mejri" class="w-32 h-32 rounded-full mb-4 shadow-md">
                    <h4 class="text-xl font-bold text-gray-100">Olfa Mejri</h4>
                    <p class="text-cyan-200 mt-2">Développeur Full-Stack</p>
                    <p class="mt-3 text-center text-sm text-gray-300">
                        Olfa apporte son expertise technique pour garantir la qualité et la performance des projets.
                    </p>
                </div>
            </div>
        </section>

        <!-- Mission / Vision Section -->
        <section class="text-center max-w-4xl space-y-4 mt-16">
            <h3 class="text-4xl font-semibold ">Notre Vision</h3>
            <p class="text-gray-200 text-lg leading-relaxed">
                Nous croyons que la technologie doit rapprocher les gens et simplifier la vie quotidienne. 
                Chaque projet est conçu pour créer de la valeur et inspirer confiance.
            </p>
            <p class="text-gray-200 text-lg leading-relaxed">
                Notre équipe s'engage à innover constamment, à apprendre de chaque expérience et à partager notre succès avec notre communauté.
            </p>
        </section>
        <br>  <br> <br> <br>
    </main>

    
</div>
@endsection
