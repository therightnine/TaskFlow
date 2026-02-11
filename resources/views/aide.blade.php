@extends('layouts.app')

@section('title', 'Centre d’aide')

@section('content')
<div class="min-h-screen bg-white">

    <main class="max-w-6xl mx-auto px-6 py-20 space-y-16">

        <!-- Header -->
        <section class="text-center space-y-6">
            <h1 class="text-5xl font-bold text-cyan-500 animate-bounce">
                Centre d’aide
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Trouvez rapidement des réponses à vos questions et apprenez à tirer le meilleur parti de TaskFlow.
            </p>

            <!-- Search -->
            <div class="max-w-xl mx-auto mt-6">
                <input type="text"
                       placeholder="Rechercher une aide..."
                       class="w-full px-6 py-4 rounded-full border border-gray-300
                              focus:outline-none focus:ring-2 focus:ring-cyan-500
                              shadow-sm">
            </div>
        </section>

        <!-- FAQ Grid -->
        <section class="grid md:grid-cols-2 gap-8">

            @foreach([
                'Comment créer un projet ?',
                'Comment inviter un membre ?',
                'Comment changer mon abonnement ?',
                'Comment réinitialiser mon mot de passe ?'
            ] as $question)

            <div class="p-8 border border-gray-200 rounded-2xl 
                        hover:shadow-lg transition duration-300 
                        transform hover:-translate-y-1">
                <h3 class="text-xl font-semibold text-gray-900 mb-3">
                    {{ $question }}
                </h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Découvrez les étapes détaillées et les meilleures pratiques pour gérer cette fonctionnalité efficacement.
                </p>
            </div>

            @endforeach

        </section>

    </main>
</div>
@endsection
