@extends('layouts.app')

@section('title', 'Conditions')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-900">
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-br from-cyan-600 via-cyan-500 to-sky-500 text-white">
        <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-black/10 blur-2xl"></div>
        <div class="relative max-w-6xl mx-auto px-6 py-14 space-y-6">
            <div class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-4 py-1 text-xs">
                TaskFlow - Cadre contractuel
            </div>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight">Conditions d'utilisation</h1>
            <p class="max-w-3xl text-cyan-50 text-sm md:text-base">
                Ces conditions definissent les regles applicables a l'utilisation de TaskFlow.
                Elles couvrent les comptes, les obligations des utilisateurs, la disponibilite du service,
                la propriete intellectuelle et les limites de responsabilite.
            </p>
            <p class="text-xs text-cyan-100">Derniere mise a jour: {{ now()->format('d/m/Y') }}</p>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-1 lg:grid-cols-12 gap-8">
        <aside class="lg:col-span-4">
            <div class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-800 mb-3">Navigation</h2>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('terms') }}" class="block rounded-lg bg-cyan-50 text-cyan-700 px-3 py-2">Conditions</a></li>
                    <li><a href="{{ route('privacy') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-50">Confidentialite</a></li>
                    <li><a href="{{ route('security') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-50">Securite</a></li>
                </ul>
                <div class="mt-5 rounded-xl bg-slate-50 border border-slate-200 p-4 text-xs text-slate-600">
                    Ce contenu est informatif et doit etre adapte a votre cadre legal local avant publication officielle.
                </div>
            </div>
        </aside>

        <main class="lg:col-span-8 space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">1. Objet et champ d'application</h3>
                <p class="text-sm text-slate-700 leading-7">
                    Les presentes conditions regissent l'acces et l'utilisation de la plateforme TaskFlow, y compris les tableaux de bord,
                    la gestion de projets, la collaboration d'equipe, les taches, les commentaires et les parametres de compte.
                    En creant un compte ou en utilisant le service, vous acceptez ces conditions sans reserve.
                </p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">2. Conditions d'acces au service</h3>
                <ul class="space-y-2 text-sm text-slate-700 leading-7 list-disc pl-5">
                    <li>Vous devez fournir des informations exactes et a jour lors de l'inscription.</li>
                    <li>Vous etes responsable de la confidentialite de vos identifiants.</li>
                    <li>Vous devez signaler sans delai tout acces non autorise a votre compte.</li>
                    <li>Nous pouvons suspendre un compte en cas d'usage frauduleux, illicite ou contraire aux presentes conditions.</li>
                </ul>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">3. Usages autorises et usages interdits</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                        <p class="font-semibold text-emerald-800 mb-2">Usages autorises</p>
                        <ul class="text-sm text-emerald-900 list-disc pl-5 space-y-1">
                            <li>Pilotage d'activites professionnelles et d'equipe.</li>
                            <li>Partage d'information operationnelle liee aux projets.</li>
                            <li>Collaboration interne dans le respect des droits d'acces.</li>
                        </ul>
                    </div>
                    <div class="rounded-xl border border-rose-200 bg-rose-50 p-4">
                        <p class="font-semibold text-rose-800 mb-2">Usages interdits</p>
                        <ul class="text-sm text-rose-900 list-disc pl-5 space-y-1">
                            <li>Tentatives d'intrusion, de reverse engineering ou de contournement de securite.</li>
                            <li>Publication de contenu illicite, diffamatoire, discriminatoire ou malveillant.</li>
                            <li>Usage du service pour spam, phishing, malware ou exploitation non autorisee des donnees.</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">4. Donnees, contenu et responsabilites</h3>
                <p class="text-sm text-slate-700 leading-7">
                    Chaque utilisateur reste responsable des contenus qu'il cree ou partage dans la plateforme
                    (projets, taches, commentaires, pieces jointes). Vous garantissez disposer des droits necessaires sur ces contenus.
                    Vous vous engagez a ne pas importer de donnees sensibles non necessaires ou interdites par votre politique interne.
                </p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">5. Propriete intellectuelle</h3>
                <p class="text-sm text-slate-700 leading-7">
                    Les elements de la plateforme (design, interface, code, structure, logos, contenus editoriaux) sont proteges
                    par les droits de propriete intellectuelle. Toute reproduction, extraction massive, adaptation ou reutilisation
                    non autorisee est interdite, sauf accord ecrit prealable.
                </p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">6. Disponibilite, maintenance et evolutions</h3>
                <p class="text-sm text-slate-700 leading-7">
                    Nous faisons des efforts raisonnables pour assurer la disponibilite du service, sans garantie d'absence totale
                    d'interruption. Des operations de maintenance, de securisation ou de mise a jour peuvent entrainer des indisponibilites
                    temporaires. Les fonctionnalites peuvent evoluer pour ameliorer le produit ou respecter des obligations techniques et legales.
                </p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">7. Limitation de responsabilite</h3>
                <p class="text-sm text-slate-700 leading-7">
                    Dans les limites autorisees par la loi, nous ne sommes pas responsables des pertes indirectes, pertes d'exploitation,
                    pertes de revenus, pertes de donnees ou dommages reputes consecutifs a l'utilisation du service.
                    L'utilisateur reste responsable des sauvegardes operationnelles necessaires a son activite.
                </p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">8. Modification des conditions</h3>
                <p class="text-sm text-slate-700 leading-7">
                    Ces conditions peuvent etre mises a jour pour refleter des changements produit, reglementaires ou organisationnels.
                    La date de mise a jour visible en haut de page fait foi. La poursuite de l'utilisation du service apres publication
                    d'une version modifiee vaut acceptation de cette version.
                </p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold mb-3">9. Contact</h3>
                <p class="text-sm text-slate-700 leading-7">
                    Pour toute question relative a ces conditions, vous pouvez contacter l'equipe support via
                    <a href="{{ route('support') }}" class="text-cyan-700 underline underline-offset-2">la page support</a>.
                </p>
            </section>
        </main>
    </div>
</div>
@endsection
