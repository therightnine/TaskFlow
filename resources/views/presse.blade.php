@extends('layouts.app')

@section('title', 'Presse')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-white via-cyan-500 to-zinc-900 text-gray-100 flex flex-col">

    <!-- Main Content -->
    <main class="flex-1 p-10 flex flex-col items-center space-y-12">

        <!-- Intro Section -->
        <section class="text-center max-w-3xl space-y-6">
            <h2 class="text-5xl font-bold animate-bounce text-cyan-500">Actualités et Communiqués</h2>
            <p class="text-gray-900 text-lg leading-relaxed">
                Découvrez nos dernières actualités, articles de presse et annonces importantes.
                Nous partageons nos innovations, partenariats et réussites avec notre communauté et le monde entier.
            </p>
        </section>

        <!-- Carousel Section -->
        <section class="relative w-full max-w-7xl flex flex-col items-center">
            
            <!-- Carousel Container -->
            <div class="overflow-hidden w-full relative">
                <div id="news-carousel" class="flex space-x-6 transition-transform duration-300 overflow-x-auto scroll-smooth hide-scrollbar">
                    @foreach([
                        ['title'=>'Lancement du Produit X','desc'=>'Découvrez notre dernier produit révolutionnaire et ses fonctionnalités innovantes.','link'=>'https://techcrunch.com/2023/09/01/example-product-launch/'],
                        ['title'=>'Interview dans TechMag','desc'=>'Notre CEO parle de nos projets futurs et de l’innovation dans le secteur tech.','link'=>'https://www.theverge.com/2023/08/25/example-interview'],
                        ['title'=>'Partenariat Stratégique','desc'=>'Nous avons établi un partenariat stratégique pour étendre nos solutions à l’international.','link'=>'https://www.forbes.com/sites/example/2023/07/20/example-strategic-partnership/'],
                        ['title'=>'Couverture dans Wired','desc'=>'Nos dernières innovations technologiques présentées dans Wired Magazine.','link'=>'https://www.wired.com/story/example-coverage/'],
                        ['title'=>'Prix de l’Innovation','desc'=>'Récompensés pour notre solution innovante qui change la manière de travailler.','link'=>'https://www.forbes.com/sites/example/innovation-award/'],
                        ['title'=>'Article dans TechRadar','desc'=>'Présentation de nos projets majeurs et de leur impact sur l’industrie technologique.','link'=>'https://www.techradar.com/news/example-article'],
                    ] as $news)
                    <div class="min-w-[300px] flex-shrink-0 bg-white bg-opacity-10 backdrop-blur-md p-6 rounded-2xl flex flex-col justify-between hover:scale-105 hover:bg-opacity-20 transition transform shadow-lg">
                        <h3 class="text-2xl font-semibold text-gray-100 mb-2">{{ $news['title'] }}</h3>
                        <p class="text-gray-800 text-sm mb-4">{{ $news['desc'] }}</p>
                        <a href="{{ $news['link'] }}" target="_blank" class="text-cyan-200 font-semibold hover:underline">Lire plus →</a>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Carousel Buttons -->
            <div class="flex justify-between w-full max-w-7xl mt-6 px-4">
                <button id="prev" class="w-12 h-12 rounded-full border-2 border-white flex items-center justify-center text-white hover:bg-white hover:text-black transition-all duration-300 font-bold text-xl shadow-lg">
                    &lt;
                </button>
                <button id="next" class="w-12 h-12 rounded-full border-2 border-white flex items-center justify-center text-white hover:bg-white hover:text-black transition-all duration-300 font-bold text-xl shadow-lg">
                    &gt;
                </button>
            </div>


        </section>

        <!-- Newsletter Section -->
        <section class="text-center max-w-4xl space-y-4 mt-16">
            <h3 class="text-4xl font-semibold text-gray-900">Restez Informé</h3>
            <p class="text-gray-200 text-lg leading-relaxed">
                Abonnez-vous à notre newsletter pour recevoir toutes nos actualités directement dans votre boîte mail.
            </p>
            <form class="flex flex-col sm:flex-row justify-center items-center gap-4 mt-4 w-full max-w-xl mx-auto">
                <input type="email" placeholder="Votre email" class="px-4 py-2 rounded-lg w-full sm:flex-1 text-gray-900 text-center" />
                <button type="submit" class="px-6 py-2 bg-cyan-500 hover:bg-cyan-400 rounded-lg text-gray-900 font-semibold transition-all duration-300">
                    S'abonner
                </button>
            </form>
        </section>

    </main>
</div>

<style>
/* Hide default scrollbar */
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<!-- Carousel Scroll JS -->
<script>
    const container = document.getElementById('news-carousel');
    const cardWidth = 300 + 24; // card width + gap
    const cardsPerClick = 3;

    document.getElementById('next').addEventListener('click', () => {
        container.scrollBy({ left: cardWidth * cardsPerClick, behavior: 'smooth' });
    });
    document.getElementById('prev').addEventListener('click', () => {
        container.scrollBy({ left: -cardWidth * cardsPerClick, behavior: 'smooth' });
    });
</script>
@endsection
