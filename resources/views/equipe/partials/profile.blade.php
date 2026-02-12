<div class="bg-white">
    <div class="h-36 bg-gradient-to-r from-slate-800 via-cyan-700 to-slate-700"></div>

    <div class="px-6 md:px-8 -mt-14">
        <div class="flex flex-col md:flex-row md:items-end gap-4">
            <img
                src="{{ $user->photo ? asset($user->photo) : asset('images/default-avatar.png') }}"
                class="w-24 h-24 md:w-28 md:h-28 rounded-2xl border-4 border-white object-cover shadow-md"
                alt="Photo de {{ $user->prenom }} {{ $user->nom }}"
            />

            <div class="pb-2">
                <br>  <br> <br> <br>
                <h2 class="text-2xl font-bold text-slate-800">{{ $user->prenom }} {{ $user->nom }}</h2>
                <p class="text-slate-500">{{ $user->role->role ?? 'Membre' }}</p>
            </div>
        </div>
    </div>

    <div class="px-6 md:px-8 py-6 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                <p class="text-xs text-slate-500">Projets</p>
                <p class="text-lg font-semibold text-slate-800">{{ $projets->count() }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                <p class="text-xs text-slate-500">Membre depuis</p>
                @if($membreDepuis)
                    <p class="text-lg font-semibold text-slate-800">{{ \Carbon\Carbon::parse($membreDepuis)->format('d M Y') }}</p>
                @else
                    <p class="text-lg font-semibold text-slate-400">Non defini</p>
                @endif
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                <p class="text-xs text-slate-500">Localisation</p>
                <p class="text-lg font-semibold text-slate-800">Tunisia</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-slate-200 p-4">
                <p class="text-xs uppercase tracking-wide text-cyan-700 font-semibold">Contact</p>
                <div class="mt-3 space-y-2 text-slate-700">
                    <p><span class="text-slate-500">Email:</span> {{ $user->email }}</p>
                    @if($user->phone)
                        <p><span class="text-slate-500">Telephone:</span> {{ $user->phone }}</p>
                    @endif
                    @if($user->date_naissance)
                        <p><span class="text-slate-500">Date de naissance:</span> {{ \Carbon\Carbon::parse($user->date_naissance)->format('d M Y') }}</p>
                    @endif
                    @if($user->profession)
                        <p><span class="text-slate-500">Profession:</span> {{ $user->profession }}</p>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 p-4">
                <p class="text-xs uppercase tracking-wide text-cyan-700 font-semibold">A propos</p>
                <div class="mt-3 text-slate-700 leading-relaxed">
                    @if($user->bio)
                        <p>{{ $user->bio }}</p>
                    @else
                        <p class="italic text-slate-400">Aucune biographie renseignee.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 p-4">
            <p class="text-xs uppercase tracking-wide text-cyan-700 font-semibold mb-3">Projets</p>
            <div class="flex flex-wrap gap-2">
                @forelse($projets as $projet)
                    <span class="px-3 py-1.5 rounded-full text-sm bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $projet->nom_projet }}
                    </span>
                @empty
                    <span class="text-slate-400 italic">Aucun projet</span>
                @endforelse
            </div>
        </div>
    </div>
</div>
