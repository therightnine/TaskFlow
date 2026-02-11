@extends('layouts.app')

@section('title', 'Carrières - TaskFlow')

@section('content')

<div class="bg-white">

    <!-- HERO -->
    <section class="max-w-[1200px] mx-auto px-8 py-24 text-center">
        <h1 class="text-4xl md:text-5xl font-medium text-black animate-bounce">
            Rejoignez l’équipe <span class="text-cyan-500">TaskFlow</span>
        </h1>

        <p class="mt-6 text-lg text-gray-600 max-w-2xl mx-auto">
            Construisez des outils qui simplifient la gestion de projets.
            Travaillez dans un environnement moderne, flexible et ambitieux.
        </p>
    </section>


    <!-- JOBS SECTION -->
    <section class="max-w-[1200px] mx-auto px-8 pb-24">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            @foreach([
                [
                    'title' => 'Développeur Full Stack',
                    'desc' => 'Participer au développement de nouvelles fonctionnalités et optimiser notre plateforme.'
                ],
                [
                    'title' => 'Designer UX/UI',
                    'desc' => 'Créer des interfaces élégantes et intuitives pour améliorer l’expérience utilisateur.'
                ],
                [
                    'title' => 'Chef de Projet Digital',
                    'desc' => 'Coordonner les équipes et assurer le bon déroulement des projets clients.'
                ]
            ] as $job)

                <div class="group border rounded-2xl p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">

                    <h3 class="text-xl font-semibold text-black">
                        {{ $job['title'] }}
                    </h3>

                    <p class="mt-4 text-gray-600 leading-relaxed">
                        {{ $job['desc'] }}
                    </p>

                    <a href="{{ route('contact') }}"
                       class="inline-block mt-6 px-6 py-3 bg-cyan-500 text-white rounded-xl
                              transition-all duration-300
                              hover:bg-white hover:text-cyan-500 hover:border hover:border-cyan-500">
                        Postuler
                    </a>

                </div>

            @endforeach

        </div>

    </section>


    <!-- CULTURE SECTION -->
    <section class="bg-gray-50 py-24">
        <div class="max-w-[1000px] mx-auto px-8 text-center">
            <h2 class="text-3xl font-medium text-black">
                Pourquoi travailler chez TaskFlow ?
            </h2>

            <p class="mt-6 text-gray-600 leading-relaxed">
                Nous croyons en la collaboration, l’autonomie et l’innovation.
                Chez TaskFlow, chaque idée compte et chaque membre contribue
                activement à l’évolution du produit.
            </p>
        </div>
    </section>

</div>

@endsection
