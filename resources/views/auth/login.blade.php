@extends('layouts.app')

@section('title', 'Connexion - TaskFlow')
@section('content_min_height_class', '')

@section('content')

<div class="w-full max-w-[1600px] mx-auto flex flex-col lg:flex-row rounded-[32px] lg:rounded-[60px] mt-4 md:mt-8 px-4 md:px-6 lg:px-0 gap-6 lg:gap-0">

    <!-- GAUCHE : FORMULAIRE DE CONNEXION -->
    <div class="w-full flex justify-center items-center bg-white">
        <div class="w-full max-w-[500px] p-6 md:p-10 lg:p-12 bg-white rounded-[28px] md:rounded-[44px] lg:rounded-[60px] shadow-xl lg:shadow-2xl shadow-black/20">

            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-zinc-900 mb-8 md:mb-10 lg:mb-12 text-center font-vietnam">
                Connexion
            </h1>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- EMAIL -->
                <label for="email" class="block text-lg md:text-2xl mb-3 text-zinc-900 font-vietnam">
                    Email
                </label>
                <div class="w-full mb-6">
                    <div class="relative">
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full bg-transparent border-b-2 border-zinc-400
                                   py-3 pr-12 text-base md:text-lg text-zinc-900
                                   focus:outline-none focus:border-cyan-500"
                        />
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-6 h-6 text-zinc-700"
                             fill="none" stroke="currentColor" stroke-width="1.5"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25H4.5
                                     A2.25 2.25 0 0 1 2.25 17.25V6.75
                                     m19.5 0A2.25 2.25 0 0 0 19.5 4.5H4.5
                                     A2.25 2.25 0 0 0 2.25 6.75
                                     m19.5 0-9.75 6.75L2.25 6.75" />
                        </svg>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- MOT DE PASSE -->
                <label for="password" class="block text-lg md:text-2xl mb-3 text-zinc-900 font-vietnam">
                    Mot de passe
                </label>
                <div class="w-full mb-6">
                    <div class="relative">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            required
                            class="w-full bg-transparent border-b-2 border-zinc-400
                                   py-3 pr-12 text-base md:text-lg text-zinc-900
                                   focus:outline-none focus:border-cyan-500"
                        />
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-6 h-6 text-zinc-700"
                             fill="none" stroke="currentColor" stroke-width="1.5"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.5 10.5V7.875a4.5 4.5 0 1 0-9 0V10.5
                                     m-.75 0h10.5
                                     a2.25 2.25 0 0 1 2.25 2.25v6
                                     a2.25 2.25 0 0 1-2.25 2.25H6
                                     a2.25 2.25 0 0 1-2.25-2.25v-6
                                     a2.25 2.25 0 0 1 2.25-2.25Z" />
                        </svg>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- MOT DE PASSE OUBLIÉ -->
                <div class="text-right mb-6">
                    <button type="button" id="forgotPasswordBtn" class="text-sky-900 text-base md:text-lg font-medium hover:underline">
                        Mot de passe oublie ?
                    </button>
                </div>

                <!-- BOUTON CONNEXION -->
                <button type="submit"
                        class="w-full py-3.5 md:py-4 bg-cyan-500 text-white text-lg md:text-2xl font-bold
                               rounded-lg hover:bg-sky-700 transition">
                    Connexion
                </button>
            </form>

            <!-- INSCRIPTION -->
            <div class="mt-6 text-center text-base md:text-lg">
                <span class="text-zinc-900">Vous n'avez pas de compte ? </span>
                <a href="{{ route('register') }}" class="text-sky-900 font-bold hover:underline">
                    Inscription
                </a>
            </div>
        </div>
    </div>

    <!-- IMAGE DROITE -->
    <div class="w-full hidden lg:flex items-center justify-center -ml-10 xl:-ml-20">
        <img src="{{ asset('images/login-right.png') }}" class="h-[420px] xl:h-[520px] object-contain">
    </div>

</div>
<br>

<!-- FORGOT PASSWORD MODAL -->
<div id="forgotPasswordModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
        <h3 class="text-xl font-bold text-zinc-900">Email Envoyé</h3>
        <p class="mt-2 text-zinc-600">
            Un email de reinitialisation du mot de passe a ete envoyé.
        </p>
        <div class="mt-5 flex justify-end">
            <button type="button" id="closeForgotModalBtn"
                class="rounded-lg bg-cyan-500 px-4 py-2 text-white font-medium hover:bg-cyan-600 transition">
                OK
            </button>
        </div>
    </div>
</div>

<script>
    const forgotPasswordBtn = document.getElementById('forgotPasswordBtn');
    const forgotPasswordModal = document.getElementById('forgotPasswordModal');
    const closeForgotModalBtn = document.getElementById('closeForgotModalBtn');

    if (forgotPasswordBtn && forgotPasswordModal && closeForgotModalBtn) {
        forgotPasswordBtn.addEventListener('click', () => {
            forgotPasswordModal.classList.remove('hidden');
            forgotPasswordModal.classList.add('flex');
        });

        closeForgotModalBtn.addEventListener('click', () => {
            forgotPasswordModal.classList.add('hidden');
            forgotPasswordModal.classList.remove('flex');
        });

        forgotPasswordModal.addEventListener('click', (event) => {
            if (event.target === forgotPasswordModal) {
                forgotPasswordModal.classList.add('hidden');
                forgotPasswordModal.classList.remove('flex');
            }
        });
    }
</script>
@endsection
