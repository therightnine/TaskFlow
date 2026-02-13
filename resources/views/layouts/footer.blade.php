<footer class="bg-zinc-900 text-white">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-8 py-12 md:py-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 md:gap-12 justify-center text-center">
        <div>
            <h3 class="text-lg font-semibold">TaskFlow</h3>
            <ul class="mt-5 space-y-3 text-sm">
                <li><a href="{{ route('home') }}" class="hover:text-cyan-400">Accueil</a></li>
                <li><a href="{{ route('fonctionalite') }}" class="hover:text-cyan-400">Fonctionnalites</a></li>
                <li><a href="{{ route('abonnements.index') }}" class="hover:text-cyan-400">Tarifs</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-cyan-400">Contact</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-lg font-semibold">Entreprise</h3>
            <ul class="mt-5 space-y-3 text-sm">
                <li><a href="{{ route('about') }}" class="hover:text-cyan-400">A propos</a></li>
                <li><a href="{{ route('careers') }}" class="hover:text-cyan-400">Carrieres</a></li>
                <li><a href="{{ route('press') }}" class="hover:text-cyan-400">Presse</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-lg font-semibold">Ressources</h3>
            <ul class="mt-5 space-y-3 text-sm">
                <li><a href="{{ route('aide') }}" class="hover:text-cyan-400">Centre d'aide</a></li>
                <li><a href="{{ route('support') }}" class="hover:text-cyan-400">Support</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-lg font-semibold">Legal</h3>
            <ul class="mt-5 space-y-3 text-sm">
                <li><a href="{{ route('terms') }}" class="hover:text-cyan-400">Conditions</a></li>
                <li><a href="{{ route('privacy') }}" class="hover:text-cyan-400">Confidentialite</a></li>
                <li><a href="{{ route('security') }}" class="hover:text-cyan-400">Securite</a></li>
            </ul>
        </div>
    </div>

    <div class="p-4 md:p-6 text-center bg-cyan-500/80 backdrop-blur-md mt-8 md:mt-10 text-white text-sm md:text-base">
        &copy; {{ date('Y') }} TaskFlow. Tous droits reserves.
    </div>
</footer>
