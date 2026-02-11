@extends('layouts.app')

@section('title', 'Contact')

@section('content')
<div class="bg-gray-50 min-h-screen">

    {{-- HERO --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-600 via-cyan-500 to-black"></div>

        <div class="relative max-w-7xl mx-auto px-6 py-20 text-center text-white">
            <h1 class="text-4xl xl:text-5xl font-bold mb-6">
                Parlons de votre projet
            </h1>
            <p class="text-indigo-100 max-w-2xl mx-auto text-lg">
                Une question, une suggestion ou besoin d’assistance ?
                L’équipe TaskFlow est là pour vous accompagner.
            </p>
        </div>
    </section>

    {{-- CONTACT SECTION --}}
    <section class="max-w-7xl mx-auto px-6 py-20 grid grid-cols-1 lg:grid-cols-2 gap-16">

        {{-- FORM --}}
        <div class="bg-white rounded-3xl shadow-md p-10 relative overflow-hidden">

            {{-- decorative blur --}}
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl"></div>

            <h2 class="text-2xl font-bold text-slate-900 mb-2">
                Envoyez-nous un message
            </h2>
            <p class="text-slate-500 mb-8">
                Nous vous répondrons dans les plus brefs délais.
            </p>

            <form method="POST" action="#" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nom --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                            Nom
                        </label>
                        <div class="relative">
                            <img src="images/user.png" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 opacity-60">
                            <input
                                type="text"
                                name="name"
                                required
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50
                                    focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition"
                                placeholder="Votre nom"
                            >
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                            Email
                        </label>
                        <div class="relative">
                            <img src="images/mail.png" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 opacity-60">
                            <input
                                type="email"
                                name="email"
                                required
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50
                                    focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition"
                                placeholder="vous@email.com"
                            >
                        </div>
                    </div>

                </div>

                {{-- Sujet --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                        Sujet
                    </label>
                    <input
                        type="text"
                        name="subject"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50
                            focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition"
                        placeholder="Objet de votre message"
                    >
                </div>

                {{-- Message --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                        Message
                    </label>
                    <textarea
                        name="message"
                        rows="5"
                        required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50
                            focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition resize-none"
                        placeholder="Décrivez votre besoin..."
                    ></textarea>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center gap-3
                        bg-gradient-to-r from-cyan-600 to-cyan-500
                        text-white px-6 py-3 rounded-xl font-semibold
                        hover:from-cyan-500 hover:to-cyan-400
                        shadow-lg shadow-cyan-500/30 transition"
                >
                    <img src="images/send.png" class="w-4 h-4">
                    Envoyer le message
                </button>
            </form>
        </div>

   

        {{-- INFO --}}
        <div class="space-y-8">

            <div>
                <h2 class="text-2xl font-bold text-slate-900 mb-4">
                    Informations de contact
                </h2>
                <p class="text-slate-600">
                    Nous répondons généralement sous 24 heures ouvrées.
                </p>
            </div>

            {{-- CARD --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                    <img src="images/mail.png" class="w-6 h-6">
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Email</h3>
                    <p class="text-slate-600">support@taskflow.com</p>
                </div>
            </div>

            {{-- CARD --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                    <img src="images/message-chatbot.png" class="w-6 h-6">
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Support</h3>
                    <p class="text-slate-600">Disponible du lundi au vendredi</p>
                </div>
            </div>

            {{-- CARD --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                    <img src="images/pinned-filled.png" class="w-6 h-6">
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Localisation</h3>
                    <p class="text-slate-600">Tunisie / Remote</p>
                </div>
            </div>

        </div>

    </section>

    

</div>
@endsection
