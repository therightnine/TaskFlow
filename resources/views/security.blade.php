@extends('layouts.app')

@section('title', 'Securite')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-900">
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-br from-cyan-600 via-cyan-500 to-sky-500 text-white">
        <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-black/10 blur-2xl"></div>
        <div class="relative max-w-6xl mx-auto px-6 py-14 space-y-6">
            <div class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-4 py-1 text-xs">
                TaskFlow - Engagement securite
            </div>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight">Securite de la plateforme</h1>
            <p class="max-w-3xl text-cyan-50 text-sm md:text-base">
                Nous mettons en place des controles techniques et organisationnels pour proteger l'integrite, la disponibilite
                et la confidentialite de vos donnees.
            </p>
            <p class="text-xs text-cyan-100">Derniere mise a jour: {{ now()->format('d/m/Y') }}</p>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-1 lg:grid-cols-12 gap-8">
        <aside class="lg:col-span-4">
            <div class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-800 mb-3">Navigation</h2>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('terms') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-50">Conditions</a></li>
                    <li><a href="{{ route('privacy') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-50">Confidentialite</a></li>
                    <li><a href="{{ route('security') }}" class="block rounded-lg bg-cyan-50 text-cyan-700 px-3 py-2">Securite</a></li>
                </ul>
                <div class="mt-5 rounded-xl bg-slate-50 border border-slate-200 p-4 text-xs text-slate-600">
                    La securite est un processus continu: prevention, detection, reponse et amelioration.
                </div>
            </div>
        </aside>

        <main class="lg:col-span-8 space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">1. Gouvernance securite</h3>
                <p class="text-sm text-slate-700 leading-7">
                    Nous appliquons des standards de securite a chaque phase du cycle de vie applicatif:
                    conception, developpement, deploiement, exploitation et supervision. Les acces sont geres
                    selon le principe du moindre privilege et revus periodiquement.
                </p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">2. Protections techniques principales</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="font-semibold text-slate-800 mb-1">Chiffrement en transit</p>
                        <p class="text-sm text-slate-700">Utilisation de protocoles HTTPS/TLS pour proteger les echanges reseau.</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="font-semibold text-slate-800 mb-1">Protection applicative</p>
                        <p class="text-sm text-slate-700">Controles d'authentification, gestion des sessions et protection CSRF.</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="font-semibold text-slate-800 mb-1">Journalisation securite</p>
                        <p class="text-sm text-slate-700">Collecte d'evenements pour tracer les actions critiques et investiguer.</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="font-semibold text-slate-800 mb-1">Durcissement environnement</p>
                        <p class="text-sm text-slate-700">Mise a jour des composants et reduction de la surface d'attaque.</p>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">3. Detection et reponse aux incidents</h3>
                <ul class="space-y-2 text-sm text-slate-700 leading-7 list-disc pl-5">
                    <li>Surveillance des anomalies et alertes operationnelles.</li>
                    <li>Processus de qualification, confinement et remediations.</li>
                    <li>Tracabilite des actions et revue post-incident.</li>
                    <li>Mesures correctives pour reduire le risque de recurrence.</li>
                </ul>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">4. Disponibilite et continuites</h3>
                <p class="text-sm text-slate-700 leading-7">
                    Nous cherchons a maintenir un niveau de disponibilite eleve via la supervision active,
                    les pratiques de sauvegarde et des procedures de reprise. Des interruptions peuvent toutefois survenir
                    lors de maintenance planifiee ou d'evenements externes majeurs.
                </p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">5. Securite cote utilisateur</h3>
                <ul class="space-y-2 text-sm text-slate-700 leading-7 list-disc pl-5">
                    <li>Utiliser un mot de passe unique et robuste.</li>
                    <li>Eviter le partage de compte entre plusieurs personnes.</li>
                    <li>Se deconnecter des postes partages.</li>
                    <li>Signaler immediatement toute activite suspecte.</li>
                </ul>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">6. Signalement de vulnerabilites</h3>
                <p class="text-sm text-slate-700 leading-7">
                    Si vous identifiez une faille potentielle, merci de la signaler de maniere responsable via
                    <a href="{{ route('support') }}" class="text-cyan-700 underline underline-offset-2">le support</a>.
                    Nous analysons les signalements et priorisons les corrections en fonction de leur impact.
                </p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">7. Amelioration continue</h3>
                <p class="text-sm text-slate-700 leading-7">
                    La securite evolue en permanence. Nos pratiques sont ajustees regulierement selon les retours terrain,
                    les nouvelles menaces, les dependances logicielles et les contraintes reglementaires.
                </p>
            </section>
        </main>
    </div>
</div>
@endsection
