<div class="max-w-6xl mx-auto bg-white rounded-3xl shadow overflow-hidden">

    {{-- Cover --}}
    <div class="h-40 bg-gradient-to-r from-slate-500 to-slate-700"></div>

    {{-- Header --}}
    <div class="flex items-center gap-6 px-8 -mt-16">
        <img src="{{ $user->photo ? asset($user->photo) : asset('images/default-avatar.png') }}"
             class="w-32 h-32 rounded-full border-4 border-white object-cover">

        <div class="mt-16">
            <h1 class="text-2xl font-bold">
                {{ $user->prenom }} {{ $user->nom }}
            </h1>
            <p class="text-slate-500">
                {{ $user->role->role ?? 'Membre' }}
            </p>
        </div>
    </div>

    {{-- Content --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 px-8 py-10">

        {{-- Left column --}}
        <div class="space-y-6">

            <div>
                <p class="text-xs text-blue-500 font-semibold uppercase">Localisation</p>
                <p class="text-lg">Tunisia</p>
            </div>

            @if($user->profession)
            <div>
                <p class="text-xs text-blue-500 font-semibold uppercase">Profession</p>
                <p class="text-lg">{{ $user->profession }}</p>
            </div>
            @endif

        </div>

        {{-- Middle column --}}
        <div class="space-y-6">

            <div>
                <p class="text-xs text-blue-500 font-semibold uppercase">Email</p>
                <p class="text-lg">{{ $user->email }}</p>
            </div>

            @if($user->phone)
            <div>
                <p class="text-xs text-blue-500 font-semibold uppercase">Téléphone</p>
                <p class="text-lg">{{ $user->phone }}</p>
            </div>
            @endif

            @if($user->date_naissance)
            <div>
                <p class="text-xs text-blue-500 font-semibold uppercase">
                    Date de naissance
                </p>
                <p class="text-lg">
                    {{ \Carbon\Carbon::parse($user->date_naissance)->format('d M Y') }}
                </p>
            </div>
            @endif

        </div>

        {{-- Right column --}}
        <div class="space-y-6">

        {{-- Membre depuis --}}
        <div>
            <p class="text-xs text-blue-500 font-semibold uppercase">
                Équipe · Membre depuis
            </p>

            @if($membreDepuis)
                <p class="text-lg text-slate-700">
                    {{ \Carbon\Carbon::parse($membreDepuis)->format('d M Y') }}
                </p>
            @else
                <p class="text-slate-400 italic">
                    Non défini
                </p>
            @endif
        </div>


    {{-- Bio --}}
    <div>
        <p class="text-xs text-blue-500 font-semibold uppercase">À propos</p>

        @if($user->bio)
            <p class="text-slate-600 leading-relaxed">
                {{ $user->bio }}
            </p>
        @else
            <p class="text-slate-400 italic">
                Aucune biographie renseignée
            </p>
        @endif
    </div>

{{-- Projets --}}
<div>
    <p class="text-xs text-blue-500 font-semibold uppercase">Projets</p>

    <ul class="list-disc ml-5 text-slate-600 space-y-1">
        @forelse($projets as $projet)
            <li>{{ $projet->nom_projet }}</li>
        @empty
            <li class="italic text-slate-400">
                Aucun projet
            </li>
        @endforelse
    </ul>
</div>


</div>

    </div>
</div>
