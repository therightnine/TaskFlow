@extends('layouts.app')

@section('title', 'Carrières')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-green-500 to-teal-600 text-white flex flex-col">
    <header class="p-6 flex justify-between items-center bg-opacity-50 bg-black">
        <h1 class="text-3xl font-bold">Opportunités de Carrière</h1>
        <nav class="space-x-6">
            <li class="inline"><a href="{{ route('about') }}" class="hover:text-yellow-300">À propos</a></li>
            <li class="inline"><a href="{{ route('careers') }}" class="hover:text-yellow-300">Carrières</a></li>
            <li class="inline"><a href="{{ route('press') }}" class="hover:text-yellow-300">Presse</a></li>
        </nav>
    </header>

    <main class="flex-1 p-10 flex flex-col items-center justify-center space-y-10">
        <h2 class="text-4xl font-semibold animate-pulse">Rejoignez notre équipe</h2>
        <p class="max-w-2xl text-lg leading-relaxed text-center">
            Nous cherchons des talents motivés pour participer à nos projets innovants. 
            Si vous aimez relever des défis et évoluer dans un environnement dynamique, vous êtes au bon endroit.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-full max-w-5xl">
            @foreach(['Développeur', 'Designer UX', 'Chef de Projet'] as $job)
                <div class="bg-black bg-opacity-40 p-6 rounded-xl hover:scale-105 transform transition-all duration-300 cursor-pointer shadow-lg">
                    <h3 class="text-2xl font-semibold">{{ $job }}</h3>
                    <p class="mt-2 text-gray-200">Postulez maintenant et faites partie de notre aventure.</p>
                    <button class="mt-4 px-4 py-2 bg-yellow-400 text-black rounded-full hover:bg-yellow-300 transition-all duration-300">
                        Postuler
                    </button>
                </div>
            @endforeach
        </div>
    </main>

    <footer class="p-6 text-center bg-black bg-opacity-50">
        &copy; {{ date('Y') }} Notre Entreprise. Tous droits réservés.
    </footer>
</div>
@endsection
