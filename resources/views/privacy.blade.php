@extends('layouts.app')

@section('title', 'Confidentialite')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-900">
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-br from-cyan-600 via-cyan-500 to-sky-500 text-white">
        <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-black/10 blur-2xl"></div>
        <div class="relative max-w-6xl mx-auto px-6 py-14 space-y-6">
            <div class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-4 py-1 text-xs">
                TaskFlow - Politique de confidentialite
            </div>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight">Protection des donnees personnelles</h1>
            <p class="max-w-3xl text-cyan-50 text-sm md:text-base">
                Cette politique explique quelles donnees nous traitons, pourquoi nous les utilisons, pendant combien de temps
                nous les conservons et quels sont vos droits.
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
                    <li><a href="{{ route('privacy') }}" class="block rounded-lg bg-cyan-50 text-cyan-700 px-3 py-2">Confidentialite</a></li>
                    <li><a href="{{ route('security') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-50">Securite</a></li>
                </ul>
                <div class="mt-5 rounded-xl bg-slate-50 border border-slate-200 p-4 text-xs text-slate-600">
                    Vous gardez la maitrise de vos donnees. Nous cherchons a appliquer le principe de minimisation.
                </div>
            </div>
        </aside>

        <main class="lg:col-span-8 space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">1. Donnees collectees</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-700">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="font-semibold mb-2">Donnees de compte</p>
                        <p>Nom, prenom, email, role, photo de profil, informations optionnelles de profil.</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="font-semibold mb-2">Donnees d'usage</p>
                        <p>Projets, taches, commentaires, equipe, interactions avec les fonctionnalites de la plateforme.</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="font-semibold mb-2">Donnees techniques</p>
                        <p>Logs applicatifs, sessions, informations de securite necessaires au bon fonctionnement.</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="font-semibold mb-2">Donnees de facturation</p>
                        <p>Abonnement choisi, date de debut, date de fin, statut d'activation du plan.</p>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">2. Finalites du traitement</h3>
                <ul class="space-y-2 text-sm text-slate-700 leading-7 list-disc pl-5">
                    <li>Creer et administrer les comptes utilisateurs.</li>
                    <li>Permettre la collaboration et la gestion des projets/taches.</li>
                    <li>Assurer la securite, detecter les abus et prevenir la fraude.</li>
                    <li>Fournir l'assistance utilisateur et traiter les demandes de support.</li>
                    <li>Ameliorer les performances, la fiabilite et l'experience produit.</li>
                </ul>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">3. Bases juridiques (reference RGPD)</h3>
                <p class="text-sm text-slate-700 leading-7">
                    Selon le contexte, les traitements peuvent reposer sur l'execution du contrat (fourniture du service),
                    le respect d'obligations legales, l'interet legitime (securite, prevention des abus, amelioration continue)
                    ou le consentement lorsque necessaire.
                </p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">4. Conservation des donnees</h3>
                <p class="text-sm text-slate-700 leading-7">
                    Les donnees sont conservees pendant la duree necessaire aux finalites pour lesquelles elles ont ete collectees,
                    puis archivees ou supprimees selon les exigences legales et operationnelles.
                    Les donnees de compte inactif peuvent etre anonymisees ou supprimees apres une periode raisonnable.
                </p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">5. Partage et sous-traitance</h3>
                <p class="text-sm text-slate-700 leading-7">
                    Nous ne vendons pas vos donnees personnelles. Certaines donnees peuvent etre traitees par des prestataires
                    techniques necessaires au fonctionnement du service (hebergement, supervision, support), uniquement sur instruction,
                    avec des engagements de confidentialite et de securite appropries.
                </p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">6. Vos droits</h3>
                <ul class="space-y-2 text-sm text-slate-700 leading-7 list-disc pl-5">
                    <li>Droit d'acces a vos donnees.</li>
                    <li>Droit de rectification en cas d'information inexacte.</li>
                    <li>Droit a l'effacement dans les conditions prevues par la loi.</li>
                    <li>Droit de limitation et d'opposition a certains traitements.</li>
                    <li>Droit a la portabilite des donnees lorsque applicable.</li>
                </ul>
                <p class="text-sm text-slate-700 mt-3">
                    Pour exercer ces droits, contactez le support via
                    <a href="{{ route('support') }}" class="text-cyan-700 underline underline-offset-2">la page support</a>.
                </p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">7. Cookies et sessions</h3>
                <p class="text-sm text-slate-700 leading-7">
                    La plateforme utilise des cookies techniques et des sessions pour maintenir l'authentification,
                    proteger les formulaires (CSRF), memoriser certaines preferences et assurer la stabilite applicative.
                    Vous pouvez configurer votre navigateur pour limiter certains cookies, avec impact possible sur le fonctionnement.
                </p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">8. Mise a jour de la politique</h3>
                <p class="text-sm text-slate-700 leading-7">
                    Cette politique peut evoluer en fonction des modifications produit, techniques, organisationnelles ou reglementaires.
                    La version la plus recente fait foi et la date de mise a jour est indiquee en haut de page.
                </p>
            </section>
        </main>
    </div>
</div>
@endsection
