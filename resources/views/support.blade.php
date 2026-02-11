@extends('layouts.app')

@section('title', 'Support')

@section('content')
<div class="min-h-screen bg-white">

    <main class="max-w-5xl mx-auto px-6 py-20 space-y-16">

        <!-- Header -->
        <section class="text-center space-y-6">
            <h1 class="text-5xl font-bold text-cyan-500 animate-bounce">
                Support
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Notre équipe est disponible pour vous accompagner et répondre à toutes vos demandes.
            </p>
        </section>

        <!-- Contact Card -->
        <section class="bg-white border border-gray-200 rounded-3xl p-12 shadow-sm">

            <form class="space-y-6">

                <div class="grid md:grid-cols-2 gap-6">
                    <input type="text"
                           placeholder="Nom"
                           class="px-5 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-cyan-500 outline-none">

                    <input type="email"
                           placeholder="Email"
                           class="px-5 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-cyan-500 outline-none">
                </div>

                <textarea rows="5"
                          placeholder="Décrivez votre problème..."
                          class="w-full px-5 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-cyan-500 outline-none"></textarea>

                <div class="text-center">
                    <button type="submit"
                            class="px-8 py-3 bg-cyan-600 text-white rounded-full 
                                   hover:bg-cyan-700 transition duration-300 
                                   transform hover:scale-105">
                        Envoyer la demande
                    </button>
                </div>

            </form>

        </section>

    </main>
</div>
@endsection
