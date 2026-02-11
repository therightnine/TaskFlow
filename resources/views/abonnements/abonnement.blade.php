@extends('layouts.app')
@section('title', 'Offres d’abonnement')

@section('content')

<div class="bg-white py-24">

    <div class="max-w-6xl mx-auto px-6">

        {{-- TITLE --}}
        <div class="text-center mb-14">
            <h2 class="text-4xl md:text-5xl font-medium text-black animate-bounce">
                Choisissez l’offre qui vous convient
            </h2>
            <p class="mt-4 text-gray-600 text-lg">
                Simple, transparent, sans engagement.
            </p>
        </div>

        {{-- Toggle Tabs (visual only) --}}
        <div class="flex justify-center mb-16">
            <div class="bg-gray-100 p-1 rounded-full flex gap-2">
                <button class="px-6 py-2 rounded-full bg-cyan-500 text-white text-sm font-semibold transition">
                    Individual & petites équipes
                </button>
                <button class="px-6 py-2 rounded-full text-gray-600 text-sm font-semibold hover:bg-gray-200 transition">
                    Business & entreprises
                </button>
            </div>
        </div>

        {{-- PRICING CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

            @foreach($abonnements as $index => $item)

                <div class="relative group border rounded-3xl p-8 transition-all duration-300
                            hover:-translate-y-3 hover:shadow-2xl">

                    {{-- Recommended Badge --}}
                    @if($index == 1)
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2
                                    bg-cyan-500 text-white text-xs font-semibold
                                    px-4 py-1 rounded-full shadow">
                            Recommandé
                        </div>
                    @endif

                    {{-- Plan Name --}}
                    <h3 class="text-2xl font-semibold text-black text-center">
                        {{ $item->abonnement }}
                    </h3>

                    {{-- Price --}}
                    <div class="mt-6 text-center">
                        <span class="text-4xl font-bold text-black">
                            {{ $item->prix }}
                        </span>
                        <span class="text-gray-500">TND / mois</span>
                    </div>

                    {{-- Divider --}}
                    <div class="my-8 border-t"></div>

                    {{-- Description --}}
                    <p class="text-gray-600 text-center leading-relaxed min-h-[60px]">
                        {{ $item->description }}
                    </p>

                    {{-- Button --}}
                    <div class="mt-10 text-center">
                        <a href="{{ route('register') }}"
                           class="inline-block w-full py-3 rounded-xl font-semibold
                                  bg-cyan-500 text-white
                                  transition-all duration-300
                                  hover:bg-white hover:text-cyan-500 hover:border hover:border-cyan-500">
                            Choisir cette offre
                        </a>
                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>

@endsection
