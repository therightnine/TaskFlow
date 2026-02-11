@extends('layouts.app')

@section('title', 'Sécurité')

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
        <h2 class="text-5xl font-bold text-zinc-900">Votre sécurité, notre priorité</h2>
        <p class="text-lg max-w-2xl mx-auto ">
            Nous mettons en œuvre les technologies et les protocoles les plus avancés pour protéger vos données et garantir un environnement sûr et fiable pour tous nos utilisateurs.
        </p>
        <a href="#security-features" class="mt-4 inline-block bg-white text-cyan-600 font-semibold px-8 py-3 rounded-full shadow-lg hover:bg-gray-100 transition duration-300">
            Explorer les fonctionnalités
        </a>
    </section>

    <!-- Security Features -->
    <main class="flex-1 p-10 max-w-6xl mx-auto space-y-16">

        <section id="security-features" class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
            <div class="bg-white shadow-lg p-8 rounded-2xl hover:shadow-2xl transition duration-300 flex flex-col items-center space-y-4">
                <img src="https://img.icons8.com/fluency/48/000000/lock.png" alt="Chiffrement" class="mb-2">
                <h3 class="text-2xl font-semibold text-cyan-500">Chiffrement de pointe</h3>
                <p class="text-gray-700">Toutes les données sensibles sont cryptées à l’aide des protocoles SSL et AES 256 bits pour garantir une sécurité maximale.</p>
            </div>

            <div class="bg-white shadow-lg p-8 rounded-2xl hover:shadow-2xl transition duration-300 flex flex-col items-center space-y-4">
                <img src="https://img.icons8.com/fluency/48/000000/security-checked.png" alt="Surveillance" class="mb-2">
                <h3 class="text-2xl font-semibold text-cyan-500">Surveillance 24/7</h3>
                <p class="text-gray-700">Notre équipe surveille les systèmes en continu pour détecter toute activité suspecte et réagir immédiatement.</p>
            </div>

            <div class="bg-white shadow-lg p-8 rounded-2xl hover:shadow-2xl transition duration-300 flex flex-col items-center space-y-4">
                <img src="https://img.icons8.com/fluency/40/000000/shield.png" alt="Conformité" class="mb-2">
                <h3 class="text-2xl font-semibold text-cyan-500">Conformité & Audits</h3>
                <p class="text-gray-700">Nous respectons les normes de sécurité internationales et effectuons régulièrement des audits pour maintenir la confiance de nos utilisateurs.</p>
            </div>
        </section>

        <!-- Security Layers Section -->
        <section class="bg-white rounded-3xl shadow-lg p-12 space-y-12">
            <h3 class="text-4xl font-bold text-center text-cyan-500">Notre approche en couches</h3>
            <p class="text-gray-700 max-w-3xl mx-auto text-center">
                Nous appliquons une approche multi-couches pour protéger les données, l’infrastructure et l’expérience utilisateur.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="flex space-x-4 items-start">
                    <img src="https://img.icons8.com/fluency/40/000000/shield.png"/>
                    <div>
                        <h4 class="text-xl font-semibold text-cyan-500">Pare-feu avancé</h4>
                        <p class="text-gray-700">Filtrage des accès réseau et protection contre les attaques externes.</p>
                    </div>
                </div>
                <div class="flex space-x-4 items-start">
                    <img src="https://img.icons8.com/fluency/40/000000/password.png"/>
                    <div>
                        <h4 class="text-xl font-semibold text-cyan-500">Authentification forte</h4>
                        <p class="text-gray-700">2FA et gestion sécurisée des mots de passe pour protéger les comptes utilisateurs.</p>
                    </div>
                </div>
                <div class="flex space-x-4 items-start">
                    <img src="https://img.icons8.com/fluency/40/000000/virus.png"/>
                    <div>
                        <h4 class="text-xl font-semibold text-cyan-500">Détection des menaces</h4>
                        <p class="text-gray-700">Analyse proactive pour identifier et bloquer toute activité malveillante.</p>
                    </div>
                </div>
                <div class="flex space-x-4 items-start">
                    <img src="https://img.icons8.com/fluency/48/000000/security-checked.png"/>
                    <div>
                        <h4 class="text-xl font-semibold text-cyan-500">Protection cloud</h4>
                        <p class="text-gray-700">Nos serveurs et services cloud bénéficient des meilleures pratiques de sécurité.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call-to-Action -->
        <section class="text-center space-y-6">
            <h3 class="text-3xl font-bold text-cyan-500">Vous voulez en savoir plus ?</h3>
            <p class="text-gray-700 max-w-2xl mx-auto">
                Contactez notre équipe sécurité pour comprendre comment nous protégeons vos données et garantir votre tranquillité d’esprit.
            </p>
            <a href="{{ route('contact') }}" class="inline-block bg-cyan-500 hover:bg-white text-white hover:text-cyan-600 font-semibold px-8 py-3 rounded-full shadow-lg transition duration-300">
                Contactez-nous
            </a>
        </section>

    </main>
</div>
@endsection
