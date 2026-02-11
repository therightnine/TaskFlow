@extends('layouts.app')

@section('title', 'Confidentialité')

@section('content')
<div class="min-h-screen bg-gray-50 text-gray-900 flex flex-col">

    <!-- Header -->
    <header class="p-6 flex justify-between items-center bg-white shadow-md">
        <h1 class="text-3xl font-bold text-gray-800"></h1>
        <nav class="space-x-6">
            <li class="inline"><a href="{{ route('terms') }}" class="hover:text-cyan-500 transition-colors">Conditions</a></li>
            <li class="inline"><a href="{{ route('privacy') }}" class="hover:text-cyan-500 transition-colors">Confidentialité</a></li>
            <li class="inline"><a href="{{ route('security') }}" class="hover:text-cyan-500 transition-colors">Sécurité</a></li>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-white via-cyan-500 to-cyan-400 text-white rounded-b-3xl shadow-lg py-20 px-6 text-center space-y-6">
        <h2 class="text-5xl font-bold text-zinc-900">Votre confidentialité, notre priorité</h2>
        <p class="text-lg max-w-2xl mx-auto ">
            Votre vie privée est essentielle pour nous. Nous nous engageons à protéger vos informations personnelles et à les utiliser de manière transparente et responsable.
        </p>
        
    </section>

    <!-- Main Content -->
    <main class="flex-1 p-10 max-w-5xl mx-auto space-y-16">

        <!-- Key Privacy Areas -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white shadow-lg p-6 rounded-xl hover:shadow-2xl transition-all duration-300 flex flex-col items-center text-center">
                <img src="https://img.icons8.com/ios-filled/50/00bcd4/data-in-both-directions.png" alt="Collecte de données" class="mb-4 w-12 h-12">
                <h3 class="text-2xl font-semibold text-cyan-500 mb-2">Collecte de données</h3>
                <p class="text-gray-700">
                    Nous collectons uniquement les informations nécessaires pour améliorer nos services et votre expérience utilisateur.
                </p>
            </div>

            <div class="bg-white shadow-lg p-6 rounded-xl hover:shadow-2xl transition-all duration-300 flex flex-col items-center text-center">
                <img src="https://img.icons8.com/ios-filled/50/00bcd4/lock-2.png" alt="Sécurité des données" class="mb-4 w-12 h-12">
                <h3 class="text-2xl font-semibold text-cyan-500 mb-2">Sécurité des données</h3>
                <p class="text-gray-700">
                    Vos informations sont chiffrées et protégées avec des mesures techniques et administratives avancées.
                </p>
            </div>

            <div class="bg-white shadow-lg p-6 rounded-xl hover:shadow-2xl transition-all duration-300 flex flex-col items-center text-center">
                <img src="https://img.icons8.com/ios-filled/50/00bcd4/data-in-both-directions.png" alt="Partage des informations" class="mb-4 w-12 h-12">
                <h3 class="text-2xl font-semibold text-cyan-500 mb-2">Partage des informations</h3>
                <p class="text-gray-700">
                    Nous ne partageons vos données avec aucun tiers sans votre consentement, sauf obligation légale ou pour fournir nos services.
                </p>
            </div>
        </section>

        <!-- Additional Details -->
        <section class="space-y-6">
            <h3 class="text-3xl font-semibold text-cyan-500 text-center">Vos droits et choix</h3>
            <p class="text-gray-700 leading-relaxed text-center">
                Vous pouvez accéder, corriger ou supprimer vos données personnelles à tout moment. Nous respectons vos choix et mettons tout en œuvre pour vous garantir un contrôle total sur vos informations.
            </p>
            <ul class="space-y-4 max-w-3xl mx-auto">
                <li class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-cyan-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.2l-3.5-3.5L4 14l5 5 12-12-1.5-1.5z"/></svg>
                    <span>Accès à vos données personnelles à tout moment.</span>
                </li>
                <li class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-cyan-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.2l-3.5-3.5L4 14l5 5 12-12-1.5-1.5z"/></svg>
                    <span>Possibilité de corriger ou mettre à jour vos informations.</span>
                </li>
                <li class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-cyan-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.2l-3.5-3.5L4 14l5 5 12-12-1.5-1.5z"/></svg>
                    <span>Option de suppression complète de vos données sur demande.</span>
                </li>
            </ul>
        </section>

        <!-- FAQ or Tips -->
        <section class="space-y-6">
            <h3 class="text-3xl font-semibold text-cyan-500 text-center">Conseils pour protéger votre vie privée</h3>
            <ul class="space-y-4 max-w-3xl mx-auto text-gray-700">
                <li class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-cyan-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="12"/></svg>
                    <span>Utilisez un mot de passe fort et unique pour votre compte.</span>
                </li>
                <li class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-cyan-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="12"/></svg>
                    <span>Ne partagez jamais vos informations personnelles par email non sécurisé.</span>
                </li>
                <li class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-cyan-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="12"/></svg>
                    <span>Vérifiez toujours les autorisations que vous accordez aux applications tierces.</span>
                </li>
            </ul>
        </section>

    </main>
</div>
@endsection
