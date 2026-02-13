@extends('layouts.app')

@section('title', 'A propos')

@section('content')
<div class="bg-slate-50 text-slate-900">
    <style>
        .interactive-card {
            position: relative;
            transform-style: preserve-3d;
            transition: transform 220ms ease, box-shadow 220ms ease, border-color 220ms ease;
            will-change: transform;
            overflow: hidden;
        }

        .interactive-card::after {
            content: "";
            position: absolute;
            inset: -30%;
            background: radial-gradient(circle at var(--mx, 50%) var(--my, 50%), rgba(6, 182, 212, 0.16), transparent 42%);
            opacity: 0;
            transition: opacity 220ms ease;
            pointer-events: none;
        }

        .interactive-card:hover::after {
            opacity: 1;
        }
    </style>
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-br from-cyan-600 via-cyan-500 to-sky-500 text-white">
        <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-black/10 blur-2xl"></div>
        <div class="relative max-w-6xl mx-auto px-6 py-16 space-y-6 text-center">
            <h2 class="text-4xl md:text-5xl font-bold">Bienvenue dans l'univers <span class="text-cyan-100">TaskFlow</span></h2>
            <p class="max-w-3xl mx-auto text-cyan-50 text-base md:text-lg">
                Nous sommes une equipe passionnee par l'innovation et la technologie.
                Notre mission est de creer des experiences numeriques simples, puissantes et intuitives.
            </p>
            <a href="#vision"
               class="inline-block px-8 py-3 rounded-xl bg-white text-cyan-700 font-semibold
                      transition-all duration-300 hover:bg-cyan-50 hover:-translate-y-0.5">
                Decouvrir notre vision
            </a>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm interactive-card js-interactive-card">
            <h3 class="text-lg font-semibold mb-2">Qui sommes-nous ?</h3>
            <p class="text-sm text-slate-600">TaskFlow est une entreprise produit orientee collaboration, pilotage d'equipe et execution de projets.</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm interactive-card js-interactive-card">
            <h3 class="text-lg font-semibold mb-2">Ce que nous construisons</h3>
            <p class="text-sm text-slate-600">Une plateforme qui connecte createurs, superviseurs et contributeurs dans un flux de travail clair.</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm interactive-card js-interactive-card">
            <h3 class="text-lg font-semibold mb-2">Notre engagement</h3>
            <p class="text-sm text-slate-600">Simplicite d'usage, fiabilite technique, securite et evolution continue selon les besoins clients.</p>
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
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl text-center interactive-card js-interactive-card
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

                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl text-center interactive-card js-interactive-card
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

                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl text-center interactive-card js-interactive-card
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

                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl text-center interactive-card js-interactive-card
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

    <section id="vision" class="py-20 px-6 relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-20 left-1/2 -translate-x-1/2 w-[520px] h-[520px] rounded-full bg-cyan-200/30 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-64 h-64 rounded-full bg-cyan-300/20 blur-2xl"></div>
        </div>
        <div class="max-w-5xl mx-auto space-y-8">
            <div class="text-center relative z-10">
                <span class="inline-flex items-center px-4 py-1 rounded-full bg-cyan-100 text-cyan-800 text-xs font-semibold border border-cyan-200 mb-4">
                    Vision Produit
                </span>
                <h3 class="text-3xl md:text-4xl font-bold mb-4 bg-gradient-to-r from-cyan-700 via-cyan-500 to-sky-500 bg-clip-text text-transparent">
                    Notre Vision
                </h3>
                <p class="text-cyan-900 text-lg leading-relaxed">
                    Nous croyons que la technologie doit simplifier la gestion et ameliorer la collaboration.
                    Chaque fonctionnalite est pensee pour offrir <span class="font-semibold text-cyan-700">clarte</span> et <span class="font-semibold text-cyan-700">efficacite</span>.
                </p>
            </div>

            <div class="rounded-2xl border border-cyan-200 bg-gradient-to-br from-white to-cyan-50 p-8 shadow-sm relative z-10">
                <h4 class="text-2xl font-semibold mb-4 text-cyan-800">TaskFlow en tant qu'entreprise</h4>
                <div class="space-y-4 text-cyan-900 leading-7">
                    <p>
                        TaskFlow est une entreprise digitale orientee produit, avec une vision long terme: rendre la gestion d'activites
                        plus <span class="font-semibold text-cyan-700">fluide</span> pour les equipes de toute taille, des startups aux structures etablies.
                    </p>
                    <p>
                        Nous investissons dans l'architecture, la qualite logicielle, l'ergonomie et l'analyse des retours utilisateurs
                        pour construire une plateforme <span class="font-semibold text-cyan-700">stable</span>, <span class="font-semibold text-cyan-700">evolutive</span> et utile au quotidien.
                    </p>
                    <p>
                        Notre ambition est de devenir un partenaire operationnel de confiance: centraliser les projets, accelerer
                        la communication, mieux distribuer les responsabilites et donner une vision <span class="font-semibold text-cyan-700">claire</span> de l'avancement.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                <div class="rounded-2xl border border-cyan-200 bg-white p-6 shadow-sm interactive-card js-interactive-card">
                    <h5 class="font-semibold mb-2 text-cyan-800">Culture produit</h5>
                    <p class="text-sm text-cyan-900">Decisions guidees par les besoins reels des utilisateurs et la mesure de l'impact.</p>
                </div>
                <div class="rounded-2xl border border-cyan-200 bg-white p-6 shadow-sm interactive-card js-interactive-card">
                    <h5 class="font-semibold mb-2 text-cyan-800">Excellence technique</h5>
                    <p class="text-sm text-cyan-900">Code maintenable, performance, securite et fiabilite dans chaque evolution.</p>
                </div>
                <div class="rounded-2xl border border-cyan-200 bg-white p-6 shadow-sm interactive-card js-interactive-card">
                    <h5 class="font-semibold mb-2 text-cyan-800">Accompagnement client</h5>
                    <p class="text-sm text-cyan-900">Support continu, ecoute active et amelioration collaborative de la plateforme.</p>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.js-interactive-card');

        cards.forEach((card) => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const rx = ((y / rect.height) - 0.5) * -8;
                const ry = ((x / rect.width) - 0.5) * 8;

                card.style.setProperty('--mx', `${(x / rect.width) * 100}%`);
                card.style.setProperty('--my', `${(y / rect.height) * 100}%`);
                card.style.transform = `perspective(900px) rotateX(${rx.toFixed(2)}deg) rotateY(${ry.toFixed(2)}deg) translateY(-4px)`;
                card.style.boxShadow = '0 14px 30px rgba(2, 132, 199, 0.14)';
                card.style.borderColor = 'rgba(6, 182, 212, 0.45)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
                card.style.boxShadow = '';
                card.style.borderColor = '';
            });
        });
    });
</script>
@endsection
