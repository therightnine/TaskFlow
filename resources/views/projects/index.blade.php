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


@section('title', 'Projets')
@section('page-title', 'Projets')

@section('content')
@php
    $role = auth()->user()->id_role;
@endphp

<div class="w-full py-6 px-6">
    <div class="bg-white rounded-3xl shadow-lg p-6">

        <!-- FILTER BAR -->
        <div class="flex items-center gap-6 mb-8 border-b border-gray-200 pb-3">

            @if($role == 3)
                <a href="{{ route('projects.create') }}" class="btn-primary">
                    <button
                            class="px-5 py-2 rounded-xl bg-cyan-600 text-white font-medium
                                hover:bg-cyan-700 hover:shadow-md
                                focus:outline-none focus:ring-2 focus:ring-cyan-400 transition">
                    + Nouveau Projet
                    </button>
                </a>
            @endif


            <!-- All -->
            <a href="{{ route('projects.index', ['filter'=>'all']) }}"
            class="flex items-center gap-2 pb-2 text-sm font-medium
                {{ $filter=='all'
                    ? 'text-cyan-600 border-b-2 border-cyan-600'
                    : 'text-gray-500 hover:text-cyan-600'
                }}">
                <span class="inline-block rounded-full
                            {{ $filter=='all' ? 'bg-cyan-100' : '' }} p-1">
                    <img src="{{ asset('images/ic_all.png') }}" alt="All" class="w-5 h-5">
                </span>
                <span>Tous</span>
            </a>

            <!-- Favorites -->
            <a href="{{ route('projects.index', ['filter'=>'favorites']) }}"
            class="flex items-center gap-2 pb-2 text-sm font-medium
                {{ $filter=='favorites'
                    ? 'text-cyan-600 border-b-2 border-cyan-600'
                    : 'text-gray-500 hover:text-cyan-600'
                }}">
                <span class="inline-block rounded-full
                            {{ $filter=='favorites' ? 'bg-cyan-100' : '' }} p-1">
                    <img src="{{ asset('images/ic_favorite.png') }}" alt="Favorites" class="w-5 h-5">
                </span>
                <span>Favoris</span>
            </a>

            <!-- Archived -->
            <a href="{{ route('projects.index', ['filter'=>'archived']) }}"
            class="flex items-center gap-2 pb-2 text-sm font-medium
                {{ $filter=='archived'
                    ? 'text-cyan-600 border-b-2 border-cyan-600'
                    : 'text-gray-500 hover:text-cyan-600'
                }}">
                <span class="inline-block rounded-full
                            {{ $filter=='archived' ? 'bg-cyan-100' : '' }} p-1">
                    <img src="{{ asset('images/ic_archive.png') }}" alt="Archived" class="w-5 h-5">
                </span>
                <span>Archivé</span>
            </a>

        </div>


        @php
            $etatLabels = [
                1 => 'En cours',
                2 => 'Terminé',
                3 => 'En attente',
                4 => 'Archivé'
            ];

            $projectsByEtat = $projects
                ->when($filter == 'all', fn($c) => $c->whereNotIn('id_etat',[4]))
                ->groupBy('id_etat');
        @endphp

        @if($projects->isEmpty())
            <div class="text-center text-gray-400 py-20">
                <h2 class="text-2xl font-semibold">Aucun projet trouvé</h2>
                <p class="mt-2">Vous n'êtes associé à aucun projet pour le moment.</p>
            </div>
        @else
            @foreach($etatLabels as $etatId => $label)
                @if(isset($projectsByEtat[$etatId]))
                <section class="mb-10">
                    <h2 class="text-2xl font-semibold mb-5">{{ $label }}</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($projectsByEtat[$etatId] as $project)

                        <div class="bg-white rounded-xl shadow-md p-6 flex flex-col justify-between relative    ">
                            
                        <!-- CARD TOP ACTIONS -->
                            <div class="absolute top-4 left-4 z-10">
                                <form method="POST" action="{{ route('projects.favorite', $project->id) }}">
                                    @csrf
                                    <button class="text-xl">
                                        {{ $project->is_favorite ? '⭐' : '☆' }}
                                    </button>
                                </form>
                            </div>

                            <div class="absolute top-4 right-4 z-10">
                                <div class="relative">
                                    <button onclick="toggleMenu({{ $project->id }})"
                                        class="w-8 h-8 flex items-center justify-center rounded-full
                                            hover:bg-gray-100 text-gray-500">
                                        ⋯
                                    </button>

                                    <div id="menu-{{ $project->id }}"
                                        class="hidden absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg border overflow-hidden">

                                        @if($role == 3)
                                            <a href="{{ route('projects.edit', $project->id) }}"
                                                class="block px-4 py-2 text-sm hover:bg-gray-100">
                                                ✏️ Modifier
                                            </a>
                                        @endif

                                        <form method="POST" action="{{ route('projects.favorite', $project->id) }}">
                                            @csrf
                                            <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                                                {{ $project->is_favorite ? '⭐ Défavorisé' : '☆ Favori' }}
                                            </button>
                                        </form>

                                        @if($role == 3)
                                            <form method="POST" action="{{ route('projects.archive', $project->id) }}">
                                                @csrf
                                                <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                                                    {{ $project->id_etat == 4 ? '♻️ Désarchiver' : '📦 Archiver' }}
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('projects.destroy', $project->id) }}"
                                                class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="delete-btn w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                    🗑️ Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>




                            <!-- CENTER CONTENT -->
                            <div class="text-center space-y-3">

                                <!-- DATES -->
                                <div class="text-sm text-gray-500 font-medium">
                                    {{ \Carbon\Carbon::parse($project->date_debut)->format('d M Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}
                                </div>

                                <!-- TITLE -->
                                <h3 class="text-lg font-bold text-gray-800 break-words">
                                    {{ $project->nom_projet }}
                                </h3>

                                <!-- DESCRIPTION -->
                                <p class="text-sm text-gray-600">
                                    {{ $project->description ?? '—' }}
                                </p>

                                <!-- PROGRESS (ONLY EN COURS) -->
                                @if($etatId == 1)
                                <div class="mt-4">
                                    <div class="flex justify-between text-xs mb-1">
                                        <span>Progrès</span>
                                        <span>{{ round($project->progress) }} %</span>
                                    </div>

                                    <div class="w-full bg-gray-200 h-2 rounded">
                                        <div class="h-2 rounded bg-primary"
                                            style="width: {{ $project->progress }}%">
                                        </div>
                                    </div>

                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $project->taches->where('id_etat', 3)->count() }}
                                        /
                                        {{ $project->taches->count() }}
                                        Tâches terminées
                                    </div>
                                </div>
                            

                                @endif

                                <!-- SUPERVISOR + CONTRIBUTORS -->
                                @if($etatId != 4)
                                <div class="flex items-center justify-between mt-4 relative">

                                    <!-- TIME REMAINING STICKER (LEFT) -->
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $project->time_remaining['color'] }}">
                                        ⏳ {{ $project->time_remaining['label'] }}
                                    </span>

                                    <!-- CONTRIBUTORS + ADD (RIGHT) -->
                                    <div class="flex items-center gap-2">

                                        <!-- SUPERVISORS -->
                                        @foreach($project->superviseurs as $superviseur)
                                            <img src="{{ $superviseur->photo ? asset($superviseur->photo) : asset('images/default-avatar.png') }}"
                                                class="w-8 h-8 rounded-full border-2 border-white  ring-2 ring-cyan-500 "
                                                title="Superviseur: {{ $superviseur->prenom }}">
                                        @endforeach

                                        <!-- CONTRIBUTORS -->
                                        @foreach($project->contributors as $contributor)
                                            <img src="{{ $contributor->photo ? asset($contributor->photo) : asset('images/default-avatar.png') }}"
                                                class="w-8 h-8 rounded-full border-2 border-white"
                                                title="{{ $contributor->prenom }}">
                                        @endforeach


                                        @if($role == 2 || $role == 3)
                                        <button onclick="openProjectModal({{ $project->id }})"
                                            class="w-6 h-6 flex items-center justify-center
                                                rounded-full bg-cyan-500 text-white text-sm font-bold
                                                transition-all duration-200
                                                hover:bg-white hover:text-cyan-500
                                                hover:ring-2 hover:ring-cyan-400">
                                            +
                                        </button>
                                        @endif



                                        <!-- CONTRIBUTORS/Supervisors MODAL -->
                                        <div id="modal-{{ $project->id }}" class="hidden fixed inset-0 z-50">

                                            <!-- Overlay -->
                                            <div class="absolute inset-0 bg-black/40" onclick="closeProjectModal({{ $project->id }})"></div>

                                            <!-- Modal box -->
                                            <div class="relative mx-auto mt-24 bg-white rounded-2xl shadow-xl w-[420px] p-6">

                                                <h3 class="text-lg font-semibold text-center mb-4">
                                                    {{ $userRole == 2 ? 'Sélectionner les contributeurs' : 'Sélectionner les superviseurs' }}
                                                </h3>


                                                <div class="grid grid-cols-2 gap-4">
                                                @foreach($assignables as $index => $user)
                                                        @php
                                                            $isAssigned = $userRole == 2
                                                                ? $project->contributors->contains($user->id)
                                                                : $project->superviseurs->contains($user->id);
                                                        @endphp
                                                        <div
                                                            id="project-{{ $project->id }}-user-{{ $user->id }}"
                                                            class="contributor-item flex flex-col items-center text-center p-3 rounded-xl cursor-pointer border
                                                                transition {{ $isAssigned ? 'bg-cyan-100 border-cyan-500' : 'border-gray-200 hover:bg-gray-50' }}"
                                                            onclick="toggleProjectAssignment({{ $project->id }}, {{ $user->id }})">
                                                            <img src="{{ $user->photo ? asset($user->photo) : asset('images/default-avatar.png') }}"
                                                                class="w-14 h-14 rounded-full mb-2 border">
                                                            <span class="text-sm font-medium">{{ $user->prenom }} {{ $user->nom ?? '' }}</span>
                                                        </div>
                                                    @endforeach

                                                </div>

                                                <div class="flex justify-end gap-3 mt-6">
                                                    <button onclick="saveProjectAssignment({{ $project->id }})" class="px-5 py-2 rounded-xl bg-cyan-500 text-white hover:bg-cyan-600">
                                                        Enregistrer
                                                    </button>

                                                    <button onclick="closeProjectModal({{ $project->id }})"
                                                        class="px-5 py-2 rounded-xl border hover:ring-2 hover:ring-cyan-400">
                                                        Annuler
                                                    </button>
                                                </div>

                                            </div>
                                        </div>


                                    </div>
                                </div>

                                @endif

                            </div>

                            

                        </div>
                        @endforeach
                    </div>
                </section>
                @endif
            @endforeach
        @endif

    </div> 
</div>

<script>
    function toggleContributor(id){
        document.getElementById('add-'+id).classList.toggle('hidden')
    }
</script>


<!-- SweetAlert script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // SweetAlert delete
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Vous ne pourrez pas revenir en arrière !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    const projectContributorState = {};

    function openProjectModal(projectId) {
        const modal = document.getElementById('modal-' + projectId);
        if (!modal) return;
        modal.classList.remove('hidden');

        if (!projectContributorState[projectId]) {
            projectContributorState[projectId] = { original: new Set(), pending: new Set() };
            modal.querySelectorAll('.contributor-item.bg-cyan-100').forEach(el => {
                const userId = el.id.split('-').pop();
                projectContributorState[projectId].original.add(userId);
                projectContributorState[projectId].pending.add(userId);
            });
        }
    }

    function closeProjectModal(projectId) {
        const modal = document.getElementById('modal-' + projectId);
        if (modal) modal.classList.add('hidden');
    }

    function toggleProjectAssignment(projectId, userId) {
        const el = document.getElementById(`project-${projectId}-user-${userId}`);
        const state = projectContributorState[projectId];

        if (state.pending.has(String(userId))) {
            state.pending.delete(String(userId));
            el.classList.remove('bg-cyan-100','border-cyan-500');
            el.classList.add('border-gray-200');
        } else {
            state.pending.add(String(userId));
            el.classList.add('bg-cyan-100','border-cyan-500');
        }
    }

    function saveProjectAssignment(projectId){
        const state = projectContributorState[projectId];
        const toAdd = [...state.pending].filter(id => !state.original.has(id));
        const toRemove = [...state.original].filter(id => !state.pending.has(id));
        const requests = [];

        const roleType = '{{ $userRole }}'; // 2=Supervisor, 3=Chef de Projet

        const routeSuffix = roleType == 2 ? 'contributor-toggle' : 'supervisor-toggle';

        toAdd.forEach(userId => {
            requests.push(fetch(`/projects/${projectId}/${routeSuffix}`,{
                method:'POST',
                headers:{
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },
                body: JSON.stringify({user_id:userId, assign:true})
            }));
        });

        toRemove.forEach(userId => {
            requests.push(fetch(`/projects/${projectId}/${routeSuffix}`,{
                method:'POST',
                headers:{
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },
                body: JSON.stringify({user_id:userId, assign:false})
            }));
        });

        Promise.all(requests).then(() => location.reload());
    }


</script>

<script>
    function toggleMenu(id) {
        document.querySelectorAll('[id^="menu-"]').forEach(m => {
            if (m.id !== 'menu-' + id) m.classList.add('hidden');
        });
        document.getElementById('menu-' + id).classList.toggle('hidden');
    }

    // Close menus on outside click
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('[id^="menu-"]').forEach(m => m.classList.add('hidden'));
        }
    });
</script>



@endsection
