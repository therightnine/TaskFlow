@extends('layouts.app')

@section('title', 'Inscription - TaskFlow')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-14">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
        <div class="order-2 lg:order-1">
            <div class="rounded-3xl bg-white border border-slate-100 shadow-2xl shadow-slate-900/10 p-8 md:p-10">
                <div class="mb-8">
                    <p class="text-sm font-semibold text-cyan-700 uppercase tracking-wide">Etape 1</p>
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mt-1">Creer votre compte</h1>
                    <p class="text-slate-500 mt-2">Renseignez vos informations principales pour commencer.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700 text-sm">
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Prenom</label>
                            <input type="text" name="prenom" value="{{ old('prenom') }}" required
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nom</label>
                            <input type="text" name="nom" value="{{ old('nom') }}"
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Mot de passe</label>
                            <input type="password" name="password" required
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Confirmation</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        </div>
                    </div>

                    <input type="hidden" name="id_role" value="2">

                    <button type="submit"
                        class="w-full rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-3 transition">
                        Continuer vers l'etape 2
                    </button>
                </form>
            </div>
        </div>

        <div class="order-1 lg:order-2">
            <div class="relative rounded-3xl overflow-hidden border border-cyan-100 bg-gradient-to-br from-cyan-50 via-sky-50 to-blue-50 p-10 min-h-[420px] flex items-center justify-center">
                <img src="{{ asset('images/login-right.png') }}" class="h-[320px] object-contain" alt="Inscription">
                <div class="absolute bottom-5 left-5 right-5 rounded-2xl bg-white/80 backdrop-blur border border-white p-4">
                    <p class="text-sm text-slate-700">
                        Rejoignez TaskFlow et configurez votre espace en deux etapes simples.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
