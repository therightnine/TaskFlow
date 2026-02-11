<footer class="bg-zinc-900 text-white">

    <!-- TOP FOOTER -->
    <div class="max-w-[1440px] mx-auto px-8 py-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 justify-center text-center">

        <!-- PRODUCT -->
        <div>
            <h3 class="text-lg font-semibold">TaskFlow</h3>
            <ul class="mt-5 space-y-3 text-sm">
                <li><a href="{{ route('home') }}" class="hover:text-cyan-400">Accueil</a></li>
                <li><a href="{{ route('fonctionalite') }}" class="hover:text-cyan-400">Fonctionnalités</a></li>
                <li><a href="{{ route('abonnements.index') }}" class="hover:text-cyan-400">Tarifs</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-cyan-400">Contact</a></li>
            </ul>
        </div>

        <!-- COMPANY -->
        <div>
            <h3 class="text-lg font-semibold">Entreprise</h3>
            <ul class="mt-5 space-y-3 text-sm">
                <li><a href="{{ route('about') }}" class="hover:text-cyan-400">À propos</a></li>
                <li><a href="{{ route('careers') }}" class="hover:text-cyan-400">Carrières</a></li>
                <li><a href="{{ route('press') }}" class="hover:text-cyan-400">Presse</a></li>
            </ul>
        </div>

        <!-- RESOURCES -->
        <div>
            <h3 class="text-lg font-semibold">Ressources</h3>
            <ul class="mt-5 space-y-3 text-sm">
                <li><a href="{{ route('aide') }}" class="hover:text-cyan-400">Centre d’aide</a></li>
                <li><a href="{{ route('support') }}" class="hover:text-cyan-400">Support</a></li>
            </ul>
        </div>

        <!-- LEGAL -->
        <div>
            <h3 class="text-lg font-semibold">Légal</h3>
            <ul class="mt-5 space-y-3 text-sm">
                <li><a href="{{ route('terms') }}" class="hover:text-cyan-400">Conditions</a></li>
                <li><a href="{{ route('privacy') }}" class="hover:text-cyan-400">Confidentialité</a></li>
                <li><a href="{{ route('security') }}" class="hover:text-cyan-400">Sécurité</a></li>
            </ul>
        </div>

    </div>

    <!-- BOTTOM BAR -->
    <div class="p-6 text-center bg-cyan-500 bg-opacity-80 backdrop-blur-md mt-10 text-white">
        &copy; {{ date('Y') }} TaskFlow. Tous droits réservés.
    </div>

</footer>
