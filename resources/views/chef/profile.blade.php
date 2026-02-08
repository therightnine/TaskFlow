@php
    $roleId = auth()->user()->id_role;

    switch ($roleId) {
        case 1: // Admin
            $layout = 'layouts.admin_layout';
            break;

        case 3: // Chef de projet
            $layout = 'layouts.chef_layout';
            break;

        case 2: // Superviseur
            $layout = 'layouts.superviseur_layout';
            break;

        case 4: // Contributeur
            $layout = 'layouts.contributeur_layout';
            break;

        default:
            $layout = 'layouts.app'; // fallback safety
    }
@endphp

@extends($layout)

@section('title', 'Profil')

@section('content')
<div class="space-y-8">

    {{-- EN-TÊTE AVEC BANNIÈRE --}}
    <div class="bg-white rounded-2xl shadow relative pb-5"> 
        {{-- Bannière --}}
        <div class="h-48 bg-gradient-to-r from-blue-700 via-blue-500 to-purple-600 rounded-t-2xl"></div>
        
        {{-- Avatar en bas au centre --}}
        <div class="absolute left-1/2 transform -translate-x-1/2 -translate-y-1/2 top-48">
            <img src="{{ $user->photo ? asset($user->photo) : asset('images/default-avatar.png') }}" 
                alt="Avatar"
                class="w-40 h-40 rounded-full border-4 border-white object-cover shadow-lg">
        </div>
        
        {{-- NOM & RÔLE --}}
        <div class="mt-24 text-center pb-6"> {{-- espace sous l'avatar pour le nom --}}
            <h2 class="text-xl font-semibold text-gray-800">{{ $user->prenom }} {{ $user->nom }}</h2>
            <p class="text-sm text-gray-500">{{ $user->profession ?? $user->role->role ?? '—' }}</p>
        </div>
    </div>

    {{-- CONTENU PRINCIPAL --}}
    <div class="flex gap-8">

        {{-- GAUCHE : Informations générales --}}
        <div class="flex-1 bg-white rounded-2xl shadow p-8 space-y-6">
            <h3 class="text-lg font-semibold text-gray-700">Informations générales</h3>
            <p class="text-sm text-gray-400 flex items-center gap-2">
                <span id="bioText">{{ $user->bio ?? "Aucune bio disponible." }}</span>
                <button id="editBioBtn" class="text-cyan-500 hover:text-blue-700">
                    ✎ {{-- ou utiliser une icône --}}
                </button>
            </p>

            {{-- Champ caché pour édition --}}
            <div id="bioEditContainer" class="hidden mt-2">
                <input type="text" id="bioInput" class="w-full p-2 border rounded" value="{{ $user->bio }}">
                <div class="flex gap-2 mt-1">
                    <button id="saveBioBtn" class="px-3 py-1 bg-cyan-500 text-white rounded hover:bg-cyan-700">Enregistrer</button>
                    <button id="cancelBioBtn" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Annuler</button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-xs text-gray-400">Prénom</p>
                    <p class="text-sm font-medium text-gray-800">{{ $user->prenom }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-xs text-gray-400">Nom</p>
                    <p class="text-sm font-medium text-gray-800">{{ $user->nom }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-xs text-gray-400">Email</p>
                    <p class="text-sm font-medium text-gray-800">{{ $user->email }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-xs text-gray-400">Téléphone</p>
                    <p class="text-sm font-medium text-gray-800">{{ $user->phone ?? '—' }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-xs text-gray-400">Date de naissance</p>
                    <p class="text-sm font-medium text-gray-800">{{ $user->date_naissance ?? '—' }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-xs text-gray-400">Rôle</p>
                    <p class="text-sm font-medium text-gray-800">{{ $user->role->role ?? '—' }}</p>
                </div>

            </div>
        </div>

        {{-- DROITE : Projets --}}
        <div class="w-80 bg-white rounded-2xl shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Projets</h3>
                <a href="{{ route('projects.index') }}" class="text-sm text-blue-500 hover:underline">Voir tous</a>
            </div>

            <div class="grid grid-cols-2 gap-4">
                @forelse($projects as $project)
                    <div class="bg-gray-50 rounded-lg p-3 shadow hover:shadow-md transition duration-200 flex flex-col justify-center items-center text-center">
                        <h4 class="text-sm font-semibold text-gray-800 mb-1 break-words">
                            {{ $project->nom_projet }}
                        </h4>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($project->date_debut)->format('d M Y') }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 col-span-2 text-center">Aucun projet assigné</p>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script>
    const editBtn = document.getElementById('editBioBtn');
    const bioText = document.getElementById('bioText');
    const bioEditContainer = document.getElementById('bioEditContainer');
    const bioInput = document.getElementById('bioInput');
    const saveBtn = document.getElementById('saveBioBtn');
    const cancelBtn = document.getElementById('cancelBioBtn');

    editBtn.addEventListener('click', () => {
        bioEditContainer.classList.remove('hidden');
        bioText.classList.add('hidden');
        bioInput.focus();
    });

    cancelBtn.addEventListener('click', () => {
        bioEditContainer.classList.add('hidden');
        bioText.classList.remove('hidden');
        bioInput.value = bioText.textContent;
    });

    saveBtn.addEventListener('click', async () => {
        const bio = bioInput.value;

        try {
            const response = await fetch("{{ route('profile.updateBio') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ bio })
            });

            const data = await response.json();

            if(response.ok){
                bioText.textContent = bio || "Aucune bio disponible.";
                bioEditContainer.classList.add('hidden');
                bioText.classList.remove('hidden');
            } else {
                console.error(data);
                alert('Échec de la mise à jour de la bio : ' + (data.message || 'Erreur inconnue'));
            }
        } catch(e){
            console.error(e);
            alert('Échec de la mise à jour de la bio : ' + e.message);
        }
    });
</script>
@endsection
