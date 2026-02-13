@extends('layouts.app')

@section('title', 'Support')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-900">
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-br from-cyan-600 via-cyan-500 to-sky-500 text-white">
        <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-black/10 blur-2xl"></div>
        <div class="relative max-w-6xl mx-auto px-6 py-14 space-y-6">
            <span class="inline-flex rounded-full border border-white/30 bg-white/10 px-4 py-1 text-xs">Assistance TaskFlow</span>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight">Support</h1>
            <p class="max-w-3xl text-cyan-50 text-sm md:text-base">
                Notre equipe est disponible pour vous accompagner et repondre a toutes vos demandes,
                qu'il s'agisse d'un blocage technique, d'une question sur les roles, les abonnements,
                la gestion des projets ou la securite de votre compte.
            </p>
        </div>
    </section>

    <main class="max-w-6xl mx-auto px-6 py-10 space-y-8">
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold mb-2">Temps de reponse</h2>
                <p class="text-sm text-slate-600">Nous traitons les demandes en priorisant les incidents bloquants.</p>
                <p class="mt-3 text-xs text-cyan-700 bg-cyan-50 border border-cyan-100 rounded-lg px-3 py-2">Support actif du lundi au vendredi</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold mb-2">Types de demande</h2>
                <p class="text-sm text-slate-600">Connexion, permissions, projet/taches, collaboration equipe, abonnement, facturation.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold mb-2">Bon reflexe</h2>
                <p class="text-sm text-slate-600">Ajoutez le role concerne, le nom du projet et une capture si possible pour accelerer la resolution.</p>
            </div>
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">
            <div class="lg:col-span-3 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <h2 class="text-2xl font-semibold mb-2">Envoyer une demande</h2>
                <p class="text-sm text-slate-600 mb-6">Decrivez votre probleme avec precision. Nous revenons vers vous avec une solution ou un plan d'action.</p>

                <form class="space-y-5">
                    <div class="grid md:grid-cols-2 gap-4">
                        <input type="text" placeholder="Nom"
                               class="px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 outline-none">
                        <input type="email" placeholder="Email"
                               class="px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 outline-none">
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <input type="text" placeholder="Sujet (ex: probleme de permissions)"
                               class="px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 outline-none">
                        <select class="px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 outline-none bg-white">
                            <option>Priorite normale</option>
                            <option>Priorite haute</option>
                            <option>Incident critique</option>
                        </select>
                    </div>

                    <textarea rows="6" placeholder="Decrivez votre probleme..."
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 outline-none"></textarea>

                    <div class="flex items-center justify-between gap-4 flex-wrap">
                        <p class="text-xs text-slate-500">En envoyant ce formulaire, vous acceptez le traitement de votre demande par notre equipe support.</p>
                        <button type="submit"
                                class="px-7 py-3 bg-cyan-600 text-white rounded-xl hover:bg-cyan-700 transition">
                            Envoyer la demande
                        </button>
                    </div>
                </form>
            </div>

            <aside class="lg:col-span-2 space-y-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="font-semibold mb-2">Avant de contacter le support</h3>
                    <ul class="text-sm text-slate-600 space-y-2 list-disc pl-5">
                        <li>Verifiez votre role (admin, createur, superviseur, contributeur).</li>
                        <li>Confirmez le projet ou la tache concernes.</li>
                        <li>Indiquez l'heure du probleme et les etapes reproduites.</li>
                    </ul>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="font-semibold mb-2">FAQ rapide</h3>
                    <div class="space-y-3 text-sm text-slate-600">
                        <p><span class="font-medium text-slate-800">Je ne vois pas mes taches ?</span><br>Verifiez d'abord le filtre projet puis votre affectation.</p>
                        <p><span class="font-medium text-slate-800">Notifications absentes ?</span><br>Reconnectez-vous puis testez avec une action recente.</p>
                        <p><span class="font-medium text-slate-800">Probleme d'acces role ?</span><br>Demandez une verification des permissions administrateur.</p>
                    </div>
                </div>
            </aside>
        </section>
    </main>
</div>
@endsection
