@php 
        $layout = auth()->user()->id_role === 3
        ? 'layouts.chef_layout'
        : 'layouts.superviseur_layout';
@endphp


@extends($layout)

@section('title', 'Équipe')
@section('page-title', 'Équipes')

@section('content')
<div class="container mx-auto p-6 bg-gray-50 rounded-3xl">

    <div class="flex items-center justify-between mb-10">
        <h1 class="text-2xl font-bold">Équipe</h1>

        <select id="projectSelect" class="border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary min-w-[220px]">
            <option value="">Tous les projets</option>
            @foreach($projets as $projet)
                <option value="{{ $projet->id }}">{{ $projet->nom_projet }}</option>
            @endforeach
        </select>
    </div>

    <span id="selectedProjectName" class="text-primary text-2xl font-bold"></span>
    <br/><br/>
    


    {{-- Team Members Grid --}}
    <div id="teamContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-10">
        @foreach($allMembers as $member)
            <div class="bg-white rounded-2xl shadow-sm p-6 text-center team-member hover:shadow-md transition cursor-pointer"
                 data-user-id="{{ $member->id }}"
                 data-projet-ids="{{ $member->allProjects()->pluck('id')->join(',') }}"
                 
                <img src="{{ $member->photo ? asset($member->photo) : asset('images/default-avatar.png') }}"
                     class="w-24 h-24 mx-auto rounded-full object-cover mb-4">

                     <img src="{{ $member->photo ? asset($member->photo) : asset('images/default-avatar.png') }}"
                class="w-24 h-24 mx-auto rounded-full object-cover mb-4">

                <h3 class="font-semibold">{{ $member->prenom }} {{ $member->nom }}</h3>
                <p class="text-sm text-gray-500">{{ $member->role->role ?? 'Membre' }}</p>
                <p class="text-xs text-gray-400">{{ $member->email }}</p>
            </div>
        @endforeach
    </div>


</div>



{{-- PROFILE PANEL --}}
<div id="profilePanel"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-5xl rounded-3xl shadow-xl overflow-hidden relative">

        <button onclick="closeProfile()"
                class="absolute top-4 right-4 text-gray-500 hover:text-black">
            ✕
        </button>

        <div id="profileContent">
            {{-- Loaded dynamically --}}
        </div>

    </div>
</div>


{{-- JS for Project Filter --}}
<script>
    const projectSelect = document.getElementById('projectSelect');
    const teamMembers = document.querySelectorAll('.team-member');
    const selectedProjectName = document.getElementById('selectedProjectName');

    projectSelect.addEventListener('change', function() {
    const selectedId = this.value;

    // Update heading
    const selectedOption = this.options[this.selectedIndex];
    if (selectedId) {
        selectedProjectName.textContent = `· ${selectedOption.text}`; // Show project name
    } else {
        selectedProjectName.textContent = ''; // Clear if "Tous les projets"
    }

    teamMembers.forEach(member => {
        const memberProjectIds = member.dataset.projetIds.split(',');
        if (!selectedId || memberProjectIds.includes(selectedId)) {
            member.style.display = 'block';
        } else {
            member.style.display = 'none';
        }
    });
});


const profilePanel = document.getElementById('profilePanel');
const profileContent = document.getElementById('profileContent');

teamMembers.forEach(card => {
    card.addEventListener('click', async () => {
        const userId = card.dataset.userId;

        profilePanel.classList.remove('hidden');
        profilePanel.classList.add('flex');

        profileContent.innerHTML = `
            <div class="p-10 text-center text-gray-500">
                Chargement...
            </div>
        `;

        const response = await fetch(`/equipe/membre/${userId}`);
        profileContent.innerHTML = await response.text();
    });
});

function closeProfile() {
    profilePanel.classList.add('hidden');
    profilePanel.classList.remove('flex');
}

</script>

@endsection

