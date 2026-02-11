@extends('layouts.app')

@section('title', 'Fonctionnalités')

@section('content')
<div class="bg-gray-50 min-h-screen">

    {{-- HERO SECTION --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-600 via-cyan-500 to-black"></div>

        <div class="relative max-w-7xl mx-auto px-6 py-20 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            {{-- TEXT --}}
            <div class="text-white">
                <h1 class="text-4xl xl:text-5xl font-bold leading-tight mb-6">
                    Gérez vos projets <br class="hidden sm:block">
                    avec clarté et efficacité
                </h1>

                <p class="text-indigo-100 text-lg mb-8 max-w-xl">
                    TaskFlow est une plateforme moderne de gestion de projets et de tâches,
                    pensée pour les équipes organisées, performantes et orientées résultats.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('dashboard.admin') }}"
                       class="bg-white text-cyan-600 px-6 py-3 rounded-xl font-semibold hover:bg-indigo-50 transition">
                        Accéder au tableau de bord
                    </a>

                    <a href="#features"
                       class="border border-white/40 px-6 py-3 rounded-xl font-semibold hover:bg-white/10 transition">
                        Découvrir les fonctionnalités
                    </a>
                </div>
            </div>

            {{-- IMAGE --}}
            <div class="relative">
                <div class="bg-white/10 backdrop-blur rounded-3xl p-4 shadow-xl">
                    <img
                        src="images/image.png"
                        alt="Aperçu dashboard"
                        class="rounded-2xl"
                    >
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    <section id="features" class="max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">
                Des fonctionnalités pensées pour les équipes modernes
            </h2>
            <p class="text-slate-600 max-w-2xl mx-auto">
                Chaque outil de TaskFlow est conçu pour améliorer la collaboration,
                la visibilité et le suivi du travail.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">

            {{-- Feature Card --}}
            @php
                $features = [
                    ['title' => 'Gestion des projets', 'desc' => 'Création, modification, archivage et attribution des rôles par projet.', 'icon' => 'images/folder-star.png'],
                    ['title' => 'Suivi des tâches', 'desc' => 'Gestion claire des statuts : en attente, en cours, terminée et archivée.', 'icon' => 'images/file-barcode.png'],
                    ['title' => 'Rôles & permissions', 'desc' => 'Admin, Chef, Superviseur, Membre et Contributeur.', 'icon' => 'images/users-plus.png'],
                    ['title' => 'Dashboards visuels', 'desc' => 'Graphiques interactifs pour suivre l’avancement en temps réel.', 'icon' => 'images/layout-dashboard.png'],
                    ['title' => 'Gestion des équipes', 'desc' => 'Visualisation des équipes par projet et profils utilisateurs.', 'icon' => 'images/users-group.png'],
                    ['title' => 'Sécurité & accès', 'desc' => 'Authentification sécurisée et accès contrôlés par rôle.', 'icon' => 'images/lock-check.png'],
                ];
            @endphp

            @foreach($features as $feature)
                <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                            <img
                                src="{{ asset($feature['icon']) }}"
                                alt="Icon"
                                class="w-6 h-6"
                            >
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">
                            {{ $feature['title'] }}
                        </h3>
                    </div>
                    <p class="text-slate-600">
                        {{ $feature['desc'] }}
                    </p>
                </div>
            @endforeach

        </div>
    </section>

    {{-- VISUAL SECTION --}}
    <section class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            <div>
                <img
                    src="{{ asset('images/hero.png') }}"
                    alt="Gestion des tâches"
                    class="rounded-3xl shadow-lg"
                >
            </div>

            <div>
                <h2 class="text-3xl font-bold text-slate-900 mb-6">
                    Une vision claire de votre travail
                </h2>
                <p class="text-slate-600 mb-6">
                    Visualisez les priorités, suivez l’avancement et anticipez les retards
                    grâce à des tableaux clairs et des graphiques précis.
                </p>

                <ul class="space-y-4">
                    <li class="flex gap-3">
                        <img src="images/file-barcode.png" class="w-5 h-5 mt-1">
                        <span class="text-slate-700">Avancement en temps réel</span>
                    </li>
                    <li class="flex gap-3">
                        <img src="images/box.png" class="w-5 h-5 mt-1">
                        <span class="text-slate-700">Répartition des tâches par rôle</span>
                    </li>
                    <li class="flex gap-3">
                        <img src="images/lock-check.png" class="w-5 h-5 mt-1">
                        <span class="text-slate-700">Historique et archivage sécurisé</span>
                    </li>
                </ul>
            </div>

        </div>
    </section>

   {{-- CTA --}}
    <div class="mt-16 bg-cyan-600 p-10 text-white text-center">
        <h2 class="text-2xl font-bold mb-4">
            Prêt à organiser votre travail plus efficacement ?
        </h2>
        <p class="text-indigo-100 mb-6">
            TaskFlow vous aide à gagner en clarté, en productivité et en collaboration.
        </p>
        <a href="{{ route('dashboard.admin') }}"
           class="inline-flex items-center gap-2 bg-white text-cyan-600 px-6 py-3 rounded-xl font-semibold hover:bg-indigo-50 transition">
            Commencer maintenant →
        </a>
    </div>

</div>
@endsection


