@extends('layouts.app')

@section('title', 'Home - TaskFlow')

@section('content')
<style>
    .home-reveal {
        opacity: 0;
        transform: translateY(16px);
        transition: opacity 500ms ease, transform 500ms ease;
    }

    .home-reveal.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    .tilt-card {
        transform-style: preserve-3d;
        transition: transform 220ms ease, box-shadow 220ms ease, border-color 220ms ease;
        will-change: transform;
    }

    .hero-chip {
        backdrop-filter: blur(8px);
    }
</style>

<!-- HERO SECTION -->
<section class="relative py-24 px-[120px] overflow-hidden bg-white">
    <div class="absolute -top-32 -left-32 w-[360px] h-[360px] bg-cyan-400/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-0 right-1/3 w-[280px] h-[280px] bg-cyan-200/30 rounded-full blur-[100px]"></div>

    <div class="relative z-10 max-w-[720px] home-reveal">
        <h1 class="text-[56px] font-medium leading-[72px] text-black animate-bounce">
            Planifiez Mieux, <br>
            Avancez Plus Vite <br>
            Avec <span class="text-cyan-500">TaskFlow</span>.
        </h1>

        <p class="mt-6 text-[18px] leading-[28px] text-gray-600 max-w-[640px]">
            Votre espace pour creer, planifier et livrer.
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

    <div class="absolute right-[80px] top-[90px] hidden xl:flex items-center gap-2 bg-white/85 border border-cyan-200 text-cyan-700 rounded-xl px-3 py-2 shadow home-reveal">
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
        <span class="text-xs font-medium">Activite en temps reel</span>
    </div>

    <div class="absolute right-[60px] bottom-[80px] hidden xl:block bg-white/90 border border-gray-200 rounded-xl px-4 py-3 shadow home-reveal">
        <p class="text-xs text-gray-500">Projects livrables</p>
        <p class="text-lg font-semibold text-cyan-700">+34% ce mois</p>
    </div>
</section>

<!-- SHOWCASE IMAGE -->
<section class="py-8 px-8 bg-white flex justify-center">
    <div class="max-w-6xl w-full space-y-6">
        <div class="rounded-3xl overflow-hidden shadow-md hover:shadow-xl transition duration-500 home-reveal tilt-card js-tilt-card">
            <img src="{{ asset('images/features.png') }}"
                 alt="Fonctionnalites"
                 class="w-full h-full object-cover">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 home-reveal">
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">Flux actifs</p>
                <p class="text-xl font-semibold text-cyan-700">128</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">Taches terminees</p>
                <p class="text-xl font-semibold text-cyan-700">2 430</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">Equipes actives</p>
                <p class="text-xl font-semibold text-cyan-700">57</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">Disponibilite</p>
                <p class="text-xl font-semibold text-cyan-700">99.9%</p>
            </div>
        </div>
    </div>
</section>


<!-- FEATURES SECTION -->
<section class="relative px-[120px] py-[120px] bg-white">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-[40px]">
        <div class="group p-10 border rounded-2xl transition-all duration-300 hover:-translate-y-2 hover:shadow-xl home-reveal tilt-card js-tilt-card">
            <div class="w-12 h-12 mb-6">
                <img src="{{ asset('images/folder-star.png') }}" alt="Gestion">
            </div>
            <h3 class="text-xl font-medium mb-3">
                Gestion Simple
            </h3>
            <p class="text-gray-600 leading-relaxed">
                Organisez vos taches et projets sans effort,
                avec une interface claire et intuitive.
            </p>
        </div>

        <div class="group p-10 border rounded-2xl transition-all duration-300 hover:-translate-y-2 hover:shadow-xl home-reveal tilt-card js-tilt-card">
            <div class="w-12 h-12 mb-6">
                <img src="{{ asset('images/users-group.png') }}" alt="Collaboration">
            </div>
            <h3 class="text-xl font-medium mb-3">
                Collaboration Fluide
            </h3>
            <p class="text-gray-600 leading-relaxed">
                Travaillez en equipe, partagez l'avancement
                et restez synchronises en temps reel.
            </p>
        </div>

        <div class="group p-10 border rounded-2xl transition-all duration-300 hover:-translate-y-2 hover:shadow-xl home-reveal tilt-card js-tilt-card">
            <div class="w-12 h-12 mb-6">
                <img src="{{ asset('images/layout-dashboard.png') }}" alt="Suivi">
            </div>
            <h3 class="text-xl font-medium mb-3">
                Suivi Intelligent
            </h3>
            <p class="text-gray-600 leading-relaxed">
                Visualisez l'etat de vos projets et prenez
                les bonnes decisions au bon moment.
            </p>
        </div>
    </div>
</section>

<section class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
    <div class="space-y-6 animate-fade-in home-reveal">
        <h2 class="text-5xl font-bold leading-tight text-gray-900">
            Construisons des experiences digitales modernes
        </h2>

        <p class="text-lg text-gray-600 leading-relaxed">
            Nous sommes une equipe passionnee par l'innovation et la technologie.
            Notre mission est de creer des solutions performantes,
            intuitives et centrees sur l'utilisateur.
        </p>

        <ul class="space-y-2 text-sm text-gray-600">
            <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-cyan-500"></span> Parcours utilisateur simplifie</li>
            <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-cyan-500"></span> Tableaux de bord clairs et exploitables</li>
            <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-cyan-500"></span> Collaboration orientee execution</li>
        </ul>

        
    </div>

    <div class="flex justify-center animate-fade-in home-reveal">
        <img src="https://images.unsplash.com/photo-1551434678-e076c223a692"
             alt="Equipe au travail"
             class="rounded-3xl shadow-xl w-full max-w-md object-cover transition duration-500 hover:scale-[1.02]">
    </div>
</section>

<br> <br> <br> <br>


<!-- ADDITIONAL ELEMENTS (without moving existing sections) -->
<section class="px-8 pb-20 bg-white">
    <div class="max-w-6xl mx-auto rounded-3xl border border-cyan-100 bg-gradient-to-r from-cyan-50 via-white to-cyan-50 p-8 home-reveal">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <p class="text-xs uppercase tracking-wide text-cyan-700 font-semibold">Nouveau</p>
                <h3 class="text-2xl font-semibold text-gray-900 mt-1">Pilotez vos objectifs avec plus de rythme</h3>
                <p class="text-sm text-gray-600 mt-2">Activez une dynamique quotidienne avec des indicateurs de progression et une meilleure priorisation des actions.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('register') }}" class="px-6 py-3 rounded-xl bg-cyan-600 text-white hover:bg-cyan-700 transition">Creer un compte</a>
                <a href="{{ route('fonctionalite') }}" class="px-6 py-3 rounded-xl border border-cyan-300 text-cyan-700 hover:bg-cyan-50 transition">Voir les fonctionnalites</a>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const reveals = document.querySelectorAll('.home-reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        reveals.forEach((el) => observer.observe(el));

        const tiltCards = document.querySelectorAll('.js-tilt-card');
        tiltCards.forEach((card) => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const rx = ((y / rect.height) - 0.5) * -6;
                const ry = ((x / rect.width) - 0.5) * 6;
                card.style.transform = `perspective(900px) rotateX(${rx.toFixed(2)}deg) rotateY(${ry.toFixed(2)}deg) translateY(-6px)`;
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
            });
        });

        const heroWrap = document.getElementById('heroImageWrap');
        const heroImage = document.getElementById('heroImage');
        if (heroWrap && heroImage) {
            heroWrap.addEventListener('mousemove', (e) => {
                const rect = heroWrap.getBoundingClientRect();
                const x = (e.clientX - rect.left) / rect.width;
                const y = (e.clientY - rect.top) / rect.height;
                heroImage.style.transform = `scale(1.08) translate(${(x - 0.5) * 10}px, ${(y - 0.5) * 10}px)`;
            });
            heroWrap.addEventListener('mouseleave', () => {
                heroImage.style.transform = '';
            });
        }
    });
</script>

@endsection
