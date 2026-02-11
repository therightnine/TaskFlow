@extends('layouts.app')

@section('title', 'Conditions')

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
        <h2 class="text-5xl font-bold text-zinc-900">Nos Conditions</h2>
        <p class="text-lg max-w-2xl mx-auto ">
                En utilisant notre site, vous acceptez nos conditions d’utilisation. Nous nous efforçons de fournir une expérience sécurisée et transparente à tous nos utilisateurs.
        </p>
        
    </section>

    <!-- Main Content -->
    <main class="flex-1 p-10 max-w-5xl mx-auto space-y-16">

        <h1 class="text-4xl font-bold text-black text-center">Conditions d’utilisation</h1>
         <p class="text-gray-700 leading-relaxed">
                Les contenus, services et fonctionnalités sont soumis à ces règles et toute violation peut entraîner une suspension de votre accès.
        </p>

        <!-- Key Conditions -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white shadow-lg p-6 rounded-xl hover:shadow-2xl transition-all duration-300 flex flex-col items-center text-center">
                <img src="https://img.icons8.com/ios-filled/50/00bcd4/user.png" alt="Accès au compte" class="mb-4 w-12 h-12">
                <h3 class="text-2xl font-semibold text-cyan-500 mb-2">Accès au compte</h3>
                <p class="text-gray-700">
                    Chaque utilisateur doit créer un compte personnel et sécurisé. Ne partagez jamais vos identifiants avec autrui.
                </p>
            </div>

            <div class="bg-white shadow-lg p-6 rounded-xl hover:shadow-2xl transition-all duration-300 flex flex-col items-center text-center">
                <img src="https://img.icons8.com/ios-filled/50/00bcd4/handshake.png" alt="Utilisation responsable" class="mb-4 w-12 h-12">
                <h3 class="text-2xl font-semibold text-cyan-500 mb-2">Utilisation responsable</h3>
                <p class="text-gray-700">
                    Il est interdit d’utiliser notre plateforme pour des activités illégales ou nuisibles à autrui. Respectez les règles et les autres utilisateurs.
                </p>
            </div>

            <div class="bg-white shadow-lg p-6 rounded-xl hover:shadow-2xl transition-all duration-300 flex flex-col items-center text-center">
                <img src="https://img.icons8.com/ios-filled/50/00bcd4/edit-file.png" alt="Modifications" class="mb-4 w-12 h-12">
                <h3 class="text-2xl font-semibold text-cyan-500 mb-2">Modifications</h3>
                <p class="text-gray-700">
                    Nous nous réservons le droit de modifier ces conditions à tout moment. Les mises à jour seront publiées sur cette page.
                </p>
            </div>
        </section>

        <!-- Additional Tips / Guidance -->
        <section class="space-y-6">
            <h3 class="text-3xl font-semibold text-cyan-500 text-center">Bonnes pratiques pour vos comptes</h3>
            <ul class="space-y-4 max-w-3xl mx-auto text-gray-700">
                <li class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-cyan-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.2l-3.5-3.5L4 14l5 5 12-12-1.5-1.5z"/>
                    </svg>
                    <span>Utilisez un mot de passe fort et unique pour votre compte.</span>
                </li>
                <li class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-cyan-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.2l-3.5-3.5L4 14l5 5 12-12-1.5-1.5z"/>
                    </svg>
                    <span>Ne partagez jamais vos identifiants et évitez les appareils publics non sécurisés.</span>
                </li>
                <li class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-cyan-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.2l-3.5-3.5L4 14l5 5 12-12-1.5-1.5z"/>
                    </svg>
                    <span>Surveillez vos activités et signalez toute utilisation suspecte de votre compte.</span>
                </li>
            </ul>
        </section>

    </main>
</div>
@endsection
