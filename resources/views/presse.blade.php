@extends('layouts.app')

@section('title', 'Presse')

@section('content')
<div class="min-h-screen bg-white text-gray-800 flex flex-col">

    <main class="flex-1 py-20 px-6 md:px-12 space-y-28">

        <!-- HERO -->
        <section class="text-center max-w-4xl mx-auto space-y-6">
            <h2 class="text-5xl font-bold text-cyan-500">
                Actualités & Communiqués
            </h2>
            <p class="text-lg text-gray-600 leading-relaxed">
                Découvrez nos dernières annonces, articles de presse et partenariats.
                Nous partageons notre évolution et nos innovations avec transparence.
            </p>
        </section>



        <!-- NEWS CAROUSEL -->
        <section class="max-w-7xl mx-auto space-y-10">

            <div class="flex justify-between items-center">
                <h3 class="text-3xl font-semibold text-gray-900">
                    À la une
                </h3>

                <div class="flex gap-4">
                    <button id="prev"
                        class="w-11 h-11 rounded-full border border-gray-300 
                               flex items-center justify-center 
                               hover:bg-gray-100 transition">
                        ‹
                    </button>
                    <button id="next"
                        class="w-11 h-11 rounded-full border border-gray-300 
                               flex items-center justify-center 
                               hover:bg-gray-100 transition">
                        ›
                    </button>
                </div>
            </div>

            <!-- Carousel Container -->
            <div class="overflow-hidden relative">
                <div id="news-carousel"
                     class="flex space-x-6 transition-transform duration-300 overflow-x-auto scroll-smooth hide-scrollbar">

                    @foreach([
                        ['title'=>'Lancement du Produit X','desc'=>'Découvrez notre dernier produit révolutionnaire et ses fonctionnalités innovantes.','link'=>'#'],
                        ['title'=>'Interview dans TechMag','desc'=>'Notre CEO partage sa vision stratégique et les perspectives du marché.','link'=>'#'],
                        ['title'=>'Partenariat Stratégique','desc'=>'Une collaboration majeure pour étendre nos solutions à l’international.','link'=>'#'],
                        ['title'=>'Couverture dans Wired','desc'=>'Nos innovations technologiques mises en avant par Wired Magazine.','link'=>'#'],
                        ['title'=>'Prix de l’Innovation','desc'=>'Récompensés pour notre solution qui transforme la gestion de projet.','link'=>'#'],
                        ['title'=>'Article TechRadar','desc'=>'Analyse approfondie de notre impact sur l’industrie technologique.','link'=>'#'],
                    ] as $news)

                    <div class="min-w-[320px] flex-shrink-0 bg-white 
                                border border-gray-200 p-6 rounded-2xl 
                                shadow-sm hover:shadow-xl 
                                transition duration-300 transform hover:-translate-y-2">

                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            {{ $news['title'] }}
                        </h3>

                        <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                            {{ $news['desc'] }}
                        </p>

                        <a href="{{ $news['link'] }}" target="_blank"
                           class="inline-flex items-center text-cyan-600 font-semibold hover:underline">
                            Lire l’article →
                        </a>
                    </div>

                    @endforeach
                </div>
            </div>

        </section>



        <!-- NEWSLETTER (Only soft gradient section) -->
        <section class="max-w-5xl mx-auto rounded-3xl p-16 
                        bg-gradient-to-r from-cyan-500 to-cyan-50
                        shadow-inner text-center space-y-6">

            <h3 class="text-4xl font-bold text-gray-900">
                Restez informé
            </h3>

            <p class="text-gray-700 text-lg leading-relaxed max-w-2xl mx-auto">
                Abonnez-vous à notre newsletter pour recevoir nos actualités
                directement dans votre boîte mail.
            </p>

            <form class="flex flex-col sm:flex-row justify-center items-center gap-4 mt-6 w-full max-w-xl mx-auto">
                <input type="email"
                       placeholder="Votre adresse email"
                       class="px-5 py-3 rounded-full w-full sm:flex-1 
                              border border-gray-300 focus:outline-none 
                              focus:ring-2 focus:ring-cyan-500">

                <button type="submit"
                        class="px-8 py-3 bg-cyan-600 text-white 
                               rounded-full font-semibold 
                               hover:bg-cyan-700 transition duration-300 
                               transform hover:scale-105">
                    S'abonner
                </button>
            </form>

        </section>

    </main>
</div>


<style>
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    const container = document.getElementById('news-carousel');
    const cardWidth = 320 + 24;

    document.getElementById('next').addEventListener('click', () => {
        container.scrollBy({ left: cardWidth * 2, behavior: 'smooth' });
    });

    document.getElementById('prev').addEventListener('click', () => {
        container.scrollBy({ left: -cardWidth * 2, behavior: 'smooth' });
    });
</script>

@endsection
