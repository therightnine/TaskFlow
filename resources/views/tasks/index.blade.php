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

@section('title', 'Tâches')
@section('page-title', 'Tâches')

@section('content')
<head>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
</style>

</head>



@php
    $role = auth()->user()->id_role;
    $taskFilter = request('filter', 'all'); // default = all

    // FILTER → STATUS NAME MAP 
    $filterToStatus = [
        'pending'  => 'En attente',
        'progress' => 'En cours',
        'done'     => 'Terminé',
        'archived' => 'Archivé',
    ];
    $statusTotals = [
        'En attente' => $tasks->get('En attente')?->count() ?? 0,
        'En cours'   => $tasks->get('En cours')?->count() ?? 0,
        'Terminé'    => $tasks->get('Terminé')?->count() ?? 0,
        'Archivé'    => $tasks->get('Archivé')?->count() ?? 0,
    ];
    $totalTasksCount = array_sum($statusTotals);
@endphp


<div class="p-6 space-y-6">

    <!-- HEADER -->
    <div class="mb-6 bg-white rounded-3xl shadow-md px-6 py-5 border border-slate-100">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                @if($selectedProject && $projects->isNotEmpty()) 
                    <span> {{ $selectedProject->nom_projet }}</span> 
                    <span class="text-xs font-medium text-cyan-700 bg-white/70 border border-cyan-200 rounded-full px-3 py-1 w-fit">
                        Suivi en temps reel
                    </span>
                @else
                    <span>Tâches</span>
                @endif
            </h1>
        <br>
        <p class="text-sm text-slate-500 mt-1">Pilotez les taches et leur avancement depuis une vue unique.</p>
        <br> 
        @if($selectedProject && $projects->isNotEmpty() && $selectedProject->deadline)
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2">
                <p class="text-amber-700">Echeance projet</p>
                <p class="text-sm font-semibold text-amber-800">{{ \Carbon\Carbon::parse($selectedProject->deadline)->format('d/m/Y') }}</p>
            </div>
        @else
            <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                <p class="text-slate-500">Echeance projet</p>
                <p class="text-sm font-semibold text-slate-600">Aucun projet selectionne</p>
            </div>
        @endif

        
    
    </div>

        <div class="flex flex-col items-start lg:items-end gap-2 w-full lg:w-auto">
            @if($selectedProject && $projects->isNotEmpty())
                <form method="GET">
                    <select name="project_id" onchange="this.form.submit()"
                        class="w-full lg:min-w-[260px] px-4 py-2.5 rounded-xl border border-cyan-300 text-sm bg-white text-gray-700
                        focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500">
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}"
                                {{ $selectedProject && $selectedProject->id == $project->id ? 'selected' : '' }}>
                                {{ $project->nom_projet }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @else
                <select disabled
                    class="w-full lg:min-w-[260px] px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-gray-100 text-gray-500 cursor-not-allowed">
                    <option >Aucun projet </option>
                </select>
            @endif
        </div>
        </div>
    </div>
    
    <div class="bg-white rounded-3xl shadow-lg p-6">
        
        <!-- TASK FILTER BAR -->
        <div class="flex items-center gap-6 mb-6 border-b border-gray-200 pb-3">

            <!-- Existing filter links -->
            <a href="{{ request()->fullUrlWithQuery(['filter' => 'all']) }}" class="flex items-center gap-2 pb-2 text-sm font-medium {{ $taskFilter=='all' ? 'text-cyan-600 border-b-2 border-cyan-600' : 'text-gray-500 hover:text-cyan-600' }}">
                <span class="inline-block rounded-full {{ $taskFilter=='all' ? 'bg-cyan-100' : '' }} p-1">
                    <img src="{{ asset('images/ic_all.png') }}" class="w-5 h-5">
                </span>
                Tous
            </a>

            <a href="{{ request()->fullUrlWithQuery(['filter' => 'pending']) }}" class="flex items-center gap-2 pb-2 text-sm font-medium {{ $taskFilter=='pending' ? 'text-cyan-600 border-b-2 border-cyan-600' : 'text-gray-500 hover:text-cyan-600' }}">
                <span class="inline-block rounded-full {{ $taskFilter=='pending' ? 'bg-cyan-100' : '' }} p-1">
                    <img src="{{ asset('images/ic_pending.png') }}" class="w-5 h-5">
                </span>
                En attente
            </a>

            <a href="{{ request()->fullUrlWithQuery(['filter' => 'progress']) }}" class="flex items-center gap-2 pb-2 text-sm font-medium {{ $taskFilter=='progress' ? 'text-cyan-600 border-b-2 border-cyan-600' : 'text-gray-500 hover:text-cyan-600' }}">
                <span class="inline-block rounded-full {{ $taskFilter=='progress' ? 'bg-cyan-100' : '' }} p-1">
                    <img src="{{ asset('images/ic_inprogress.png') }}" class="w-5 h-5">
                </span>
                En cours
            </a>

            <a href="{{ request()->fullUrlWithQuery(['filter' => 'done']) }}" class="flex items-center gap-2 pb-2 text-sm font-medium {{ $taskFilter=='done' ? 'text-cyan-600 border-b-2 border-cyan-600' : 'text-gray-500 hover:text-cyan-600' }}">
                <span class="inline-block rounded-full {{ $taskFilter=='done' ? 'bg-cyan-100' : '' }} p-1">
                    <img src="{{ asset('images/ic_done.png') }}" class="w-5 h-5">
                </span>
                Terminé
            </a>

            <a href="{{ request()->fullUrlWithQuery(['filter' => 'archived']) }}" class="flex items-center gap-2 pb-2 text-sm font-medium {{ $taskFilter=='archived' ? 'text-cyan-600 border-b-2 border-cyan-600' : 'text-gray-500 hover:text-cyan-600' }}">
                <span class="inline-block rounded-full {{ $taskFilter=='archived' ? 'bg-cyan-100' : '' }} p-1">
                    <img src="{{ asset('images/ic_archive.png') }}" class="w-5 h-5">
                </span>
                Archivé
            </a>

            @if($role == 2)
            <!-- CREATE TASK BUTTON -->
            <button onclick="openCreateTaskModal()"
                class="ml-auto px-4 py-2 bg-cyan-500 text-white rounded-xl hover:bg-cyan-600 transition">
                + Nouveau Tache
            </button>
            @endif

        </div>

        

        @if($selectedProject)
            <div class="mb-6 grid grid-cols-2 md:grid-cols-5 gap-3">
                <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-500">Total</p>
                    <p class="text-lg font-semibold text-gray-800" data-summary-count="total">{{ $totalTasksCount }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-500">En attente</p>
                    <p class="text-lg font-semibold text-gray-700" data-summary-count="1">{{ $statusTotals['En attente'] }}</p>
                </div>
                <div class="rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-3">
                    <p class="text-xs text-cyan-700">En cours</p>
                    <p class="text-lg font-semibold text-cyan-700" data-summary-count="2">{{ $statusTotals['En cours'] }}</p>
                </div>
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                    <p class="text-xs text-emerald-700">Terminé</p>
                    <p class="text-lg font-semibold text-emerald-700" data-summary-count="3">{{ $statusTotals['Terminé'] }}</p>
                </div>
                <div class="rounded-xl border border-purple-200 bg-purple-50 px-4 py-3">
                    <p class="text-xs text-purple-700">Archivé</p>
                    <p class="text-lg font-semibold text-purple-700" data-summary-count="4">{{ $statusTotals['Archivé'] }}</p>
                </div>
            </div>

            <!-- CREATE TASK MODAL -->
            <div id="task-create-modal" class="hidden fixed inset-0 z-50 overflow-auto">
                <!-- Dark overlay -->
                <div class="absolute inset-0 bg-black/40" onclick="closeCreateTaskModal()"></div>

                    <!-- Modal content -->
                    <div class="relative bg-white max-w-lg mx-auto mt-24 rounded-3xl shadow-xl p-6 space-y-4">
                        <h2 class="text-2xl font-bold">Créer une tâche</h2>

                        <form method="POST" action="{{ url(route('tasks.store')) }}">
                            @csrf

                            @if($selectedProject) 
                                <input type="hidden" name="id_projet" value="{{ $selectedProject->id }}"> 
                            @endif


                            <!-- Task Name -->
                            <div>
                                <label class="text-sm font-medium">Nom tâche</label>
                                <input type="text" name="nom_tache" required
                                        class="w-full border rounded-xl p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600 hover:ring-2 hover:ring-cyan-200">
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="text-sm font-medium">Description</label>
                                <textarea name="description" rows="3"
                                    class="w-full border rounded-xl p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600 hover:ring-2 hover:ring-cyan-200"></textarea>
                            </div>
                            <!-- Priority -->
                            <div>
                                <label class="text-sm font-medium">Priorité</label>
                                    <select name="priorite" required
                                            class="w-full border rounded-xl p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600 hover:ring-2 hover:ring-cyan-200">
                                        <option value="low">Faible</option>
                                        <option value="medium">Moyen</option>
                                        <option value="high">Élevé</option>
                                    </select>
                            </div>
                            <!-- Status for role 4 only -->
                            @if($role == 4)
                                <div>
                                    <label class="text-sm font-medium">Statut</label>
                                        <select name="id_etat" required
                                                class="w-full border rounded-xl p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600 hover:ring-2 hover:ring-cyan-200">
                                            @foreach($etats as $etat)
                                                <option value="{{ $etat->id }}">{{ $etat->etat }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            @endif
                            <!-- Deadline -->
                            <div>
                                <label class="text-sm font-medium">Deadline</label>
                                <input type="date" name="deadline" required
                                    class="w-full border rounded-xl p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600 hover:ring-2 hover:ring-cyan-200">
                            </div>
                            <!-- Buttons -->
                            <div class="flex justify-end gap-3 pt-4">
                                <button type="button" onclick="closeCreateTaskModal()"
                                        class="px-4 py-2 rounded-xl border hover:ring-2 hover:ring-cyan-400">
                                    Annuler
                                </button>

                                <button type="submit" class="px-5 py-2 rounded-xl bg-cyan-600 text-white hover:bg-cyan-700">
                                    Créer
                                </button>
                            </div>
                        </form>
            </div>
        @else
           <div class="text-center text-gray-400 py-20">
                <h2 class="text-2xl font-semibold">Aucune tâche trouvée</h2>
                <p class="mt-2">Vous n'êtes associé à aucune tâche pour le moment.</p>
            </div>
        @endif
    </div>

        <!-- TASKS LIST -->
        @if($selectedProject && $tasks->isNotEmpty()) 
            <div class="flex gap-5 overflow-x-auto overflow-y-visible pb-4 relative snap-x snap-mandatory">

                @php
                    $statuses = [
                        'En attente' => [
                            'dot' => 'bg-gray-400',
                            'line' => 'from-gray-300 to-gray-100',
                            'text' => 'text-gray-700',
                        ],
                        'En cours' => [
                            'dot' => 'bg-cyan-500',
                            'line' => 'from-cyan-400 to-cyan-100',
                            'text' => 'text-cyan-700',
                        ],
                        'Terminé' => [
                            'dot' => 'bg-emerald-500',
                            'line' => 'from-emerald-400 to-emerald-100',
                            'text' => 'text-emerald-700',
                        ],
                        'Archivé' => [
                            'dot' => 'bg-purple-500',
                            'line' => 'from-purple-400 to-purple-100',
                            'text' => 'text-purple-700',
                        ],
                    ];
                @endphp

                @foreach($statuses as $statusName => $style)

                    {{-- SKIP NON-MATCHING STATUSES WHEN FILTER IS ACTIVE --}}
                    @if($taskFilter !== 'all' && ($filterToStatus[$taskFilter] ?? null) !== $statusName)
                        @continue
                    @endif

                    <div
                        class="
                            bg-gray-50/80 rounded-2xl p-4 shadow-sm border border-gray-200 snap-start
                            {{ $taskFilter === 'all' ? 'flex-shrink-0 w-80' : 'flex-1' }}"
                        data-etat-id="{{ $etats->firstWhere('etat', $statusName)->id }}"
                        ondragover="allowDrop(event)"
                        ondrop="dropOnColumn(event)"
                    >

                        <!-- HEADER -->
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full {{ $style['dot'] }}"></span>
                                <h2 class="font-semibold text-base {{ $style['text'] }}">
                                    {{ $statusName }}
                                </h2>
                            </div>
                            @php $tasksForStatus = $tasks->get($statusName) ?? collect(); @endphp
                            <span class="inline-flex items-center justify-center min-w-[28px] h-7 px-2 rounded-full bg-white border border-gray-200 text-xs font-semibold text-gray-600" data-column-count>
                                {{ $tasksForStatus->count() }}
                            </span>
                        </div>

                        <div class="h-[2px] rounded-full bg-gradient-to-r {{ $style['line'] }}"></div>
                        <br>

                        @if($tasksForStatus->isEmpty())
                            <div class="task-empty-placeholder rounded-xl border border-dashed border-gray-300 bg-white/70 px-4 py-6 text-center">
                                <p class="text-gray-400 text-sm">Aucune tâche dans ce statut</p>
                            </div>
                        @else
                            @foreach($tasksForStatus as $task)
                                {{--TASK CARD CODE  --}}
                                <div class="relative bg-white rounded-xl p-4 mb-4 border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-lg transition-shadow duration-200 cursor-pointer"
                                        draggable="{{ $role == 4 && $task->projet->contributors->contains(auth()->user()->id) ? 'true' : 'false' }}"
                                        data-task-id="{{ $task->id }}"
                                        ondragstart="dragTask(event)"
                                        @mousedown.stop>
                                                            


                                    <!-- Add a small "⋯" menu -->
                                    <div 
                                        class="absolute top-3 right-3 "
                                        x-data="{ openMenu: false }"
                                        @click.stop
                                        @click.outside="openMenu = false"
                                    >
                                        <!-- Button -->
                                        <button
                                            @click.stop="openMenu = !openMenu"
                                            type="button"
                                            class="p-1 hover:bg-gray-100 rounded transition-all"
                                            title="Options"
                                            draggable="false"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6 text-gray-500"
                                                fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6v.01M12 12v.01M12 18v.01" />
                                            </svg>
                                        </button>

                                        <!-- Dropdown -->
                                        <div
                                            x-cloak
                                            x-show="openMenu"
                                            @click.outside="openMenu = false"
                                            x-transition.origin.top.right
                                            class="absolute right-0 mt-2 w-40 bg-white border rounded-xl shadow-lg z-[999]">
                                        
                                            <button
                                                @click="openTaskModal({{ $task->id }}); openMenu=false"
                                                class="w-full text-left px-4 py-2 hover:bg-gray-100 text-sm"
                                            >
                                               <img src="images/eye.png" alt="View Task" class="w-4 h-4 inline mr-2">  Ouvrir 
                                            </button>

                                            @if($role == 2)
                                                <div class="border-t my-1"></div>

                                                <button
                                                    @click="openUpdateModal({{ $task->id }}); openMenu=false"
                                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 text-sm"
                                                >
                                                    <img src="images/pencil.png" alt="View Task" class="w-4 h-4 inline mr-2"> Modifier
                                                </button>

                                                <div class="border-t my-1"></div>

                                               <form method="POST" action="{{ route('tasks.archive', $task->id) }}">
                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        @click="openMenuId = null"
                                                        class="w-full flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100"
                                                    >
                                                        @if($task->id_etat == 4)
                                                            <img src="{{ asset('images/unarchive.png') }}" class="w-4 h-4">
                                                            <span>Désarchiver</span>
                                                        @else
                                                            <img src="{{ asset('images/box.png') }}" class="w-4 h-4">
                                                            <span>Archiver</span>
                                                        @endif
                                                    </button>
                                                </form>


                                                <div class="border-t my-1"></div>

                                                <form method="POST" action="{{ route('tasks.destroy', $task->id) }}"
                                                    onsubmit="return confirm('Delete this task?')">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        @click="openMenu=false"
                                                        class="w-full flex text-left gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                                    >
                                                        <img src="images/trash.png" alt="View Task" class="w-4 h-4 inline mr-2"> Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>


                                    <!-- PRIORITY -->
                                    <span class="text-xs px-2 py-1 rounded-full text-center
                                        {{ $task->priorite == 'low' ? 'bg-cyan-200 text-cyan-800' : '' }}
                                        {{ $task->priorite == 'medium' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                        {{ $task->priorite == 'high' ? 'bg-red-200 text-red-800' : '' }}">
                                        {{ ucfirst($task->priorite) }}
                                    </span>


                                    <!-- TASK CONTENT -->
                                    <h3 class="font-semibold mt-2">{{ $task->nom_tache }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $task->description }}</p>

                                    <!-- FOOTER -->
                                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                                        <span>{{ $task->commentaires_count ?? 0 }} commentaires</span>
                                        <span>{{ $task->deadline }}</span>
                                    </div>

                                    <!-- CONTRIBUTORS -->
                                    <div class="flex items-center justify-between mt-4">

                                        <!-- AVATARS -->
                                        <div class="flex -space-x-2">
                                            @foreach($task->contributors as $contributor)
                                                <img
                                                    src="{{ $contributor->photo ? asset($contributor->photo) : asset('images/default-avatar.png') }}"
                                                    class="w-8 h-8 rounded-full border-2 border-white"
                                                    title="{{ $contributor->prenom }}"
                                                />
                                            @endforeach
                                        </div>

                                        <!-- ADD CONTRIBUTORS (Supervisor only) -->
                                        @if($role == 2)
                                        <div class="relative">

                                            <!-- Add Button -->
                                            <button onclick="openContributorModal({{ $task->id }})"
                                                class="w-6 h-6 flex items-center justify-center
                                                rounded-full bg-cyan-500 text-white text-sm font-bold
                                                transition-all duration-200
                                                hover:bg-white hover:text-cyan-500
                                                hover:ring-2 hover:ring-cyan-400">
                                                +
                                            </button>

                                        </div>
                                        @endif


                                    </div>

                                    

                                </div>

                                @if($role == 2)
                                    <div id="modal-{{ $task->id }}" class="hidden fixed inset-0 z-50">

                                        <!-- Overlay -->
                                        <div class="absolute inset-0 bg-black/40"
                                            onclick="closeContributorModal({{ $task->id }})"></div>

                                        <!-- Modal box -->
                                        <div class="relative mx-auto mt-24 bg-white rounded-2xl shadow-xl
                                                    w-full max-w-[420px] p-6">

                                            <h3 class="text-lg font-semibold text-center text-black mb-4">
                                                Sélectionner des contributeurs
                                            </h3>

                                            @php
                                                $contributors = $task->projet->contributors
                                                    ->where('id_role', 4)
                                                    ->values();
                                            @endphp

                                            <!-- Contributors grid -->
                                            <div class="grid grid-cols-2 gap-4">
                                                @foreach($contributors as $index => $contributor)
                                                    @php
                                                        $page = intdiv($index, 4);
                                                        $isAssigned = $task->contributors->contains($contributor->id);
                                                    @endphp

                                                    <div
                                                        class="contributor-item page-{{ $page }}
                                                        {{ $page !== 0 ? 'hidden' : '' }}
                                                        flex flex-col items-center text-center
                                                        p-3 rounded-xl cursor-pointer border
                                                        transition
                                                        {{ $isAssigned ? 'bg-cyan-100 border-cyan-500' : 'border-gray-200 hover:bg-gray-50' }}"
                                                        onclick="toggleContributor({{ $task->id }}, {{ $contributor->id }})"
                                                        id="task-{{ $task->id }}-user-{{ $contributor->id }}"
                                                    >
                                                        <img
                                                            src="{{ $contributor->photo ? asset($contributor->photo) : asset('images/default-avatar.png') }}"
                                                            class="w-14 h-14 rounded-full mb-2 border"
                                                        >
                                                        <span class="text-sm font-medium">
                                                            {{ $contributor->prenom }} {{ $contributor->nom ?? '' }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- ACTION BUTTONS -->
                                            <div class="flex justify-between gap-3 mt-6">
                                                <button
                                                    onclick="saveContributors({{ $task->id }})"
                                                    class="flex-1 py-2 rounded-xl bg-cyan-500 text-white hover:bg-cyan-600">
                                                    Enregistrer
                                                </button>
                                            </div>

                                            <!-- Pagination -->
                                            @if($contributors->count() > 4)
                                                <div class="flex justify-center gap-2 mt-5">
                                                    @for($i = 0; $i < ceil($contributors->count() / 4); $i++)
                                                        <button
                                                            onclick="changePage({{ $task->id }}, {{ $i }})"
                                                            class="w-3 h-3 rounded-full bg-cyan-300 hover:bg-cyan-500">
                                                        </button>
                                                    @endfor
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- TASK DETAIL MODAL -->
                                <div id="task-modal-{{ $task->id }}" class="hidden fixed inset-0 z-50 overflow-auto">
                                    <!-- Overlay -->
                                    <div class="absolute inset-0 bg-black/40" onclick="closeTaskModal({{ $task->id }})"></div>

                                    <!-- Modal Box -->
                                    <div class="relative bg-white max-w-lg mx-auto mt-24 rounded-3xl shadow-xl p-6 space-y-4 transition-transform duration-200">
                                        <h2 class="text-2xl font-bold">{{ $task->nom_tache }}</h2>

                                        <!-- Task Info -->
                                        <div class="grid grid-cols-2 gap-4 text-sm items-center">
                                            <div class="flex items-center gap-2">
                                                <img src="{{ asset('images/Tags.png') }}" class="w-5 h-5"/>
                                                <span>Priorité</span>
                                            </div>
                                            <div>
                                                <span class="text-xs px-2 py-1 rounded-full text-center
                                                    {{ $task->priorite == 'low' ? 'bg-cyan-200 text-cyan-800' : '' }}
                                                    {{ $task->priorite == 'medium' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                                    {{ $task->priorite == 'high' ? 'bg-red-200 text-red-800' : '' }}">
                                                    {{ ucfirst($task->priorite) }}
                                                </span>
                                            </div>

                                            <!-- Status -->
                                            <div class="flex items-center gap-2">
                                                <img src="{{ asset('images/Ok.png') }}" class="w-5 h-5"/>
                                                <span>Statut</span>
                                            </div>
                                            <div>
                                                <span class="text-xs px-2 py-1 rounded-full text-center
                                                    {{ $task->etat->etat == 'En attente' ? 'bg-gray-200 text-gray-800' : '' }}
                                                    {{ $task->etat->etat == 'En cours' ? 'bg-blue-200 text-blue-800' : '' }}
                                                    {{ $task->etat->etat == 'Terminé' ? 'bg-green-200 text-green-800' : '' }}
                                                    {{ $task->etat->etat == 'Archivé' ? 'bg-purple-200 text-purple-800' : '' }}">
                                                    {{ $task->etat->etat ?? 'En attente' }}
                                                </span>
                                            </div>

                                            <!-- Owner -->
                                            <div class="flex items-center gap-2">
                                                <img src="{{ asset('images/Person.png') }}" class="w-5 h-5"/>
                                                <span>Propriétaire</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <img src="{{ $task->projet->owner->photo ? asset($task->projet->owner->photo) : asset('images/default-avatar.png') }}"
                                                    class="w-8 h-8 rounded-full border"/>
                                                <span>{{ $task->projet->owner->prenom }} {{ $task->projet->owner->nom ?? '' }}</span>
                                            </div>

                                            <!-- Contributors -->
                                            <div class="flex items-center gap-2">
                                                <img src="{{ asset('images/Male User.png') }}" class="w-5 h-5"/>
                                                <span>Contributeurs</span>
                                            </div>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($task->contributors as $contributor)
                                                    <div class="flex items-center gap-2">
                                                        <img src="{{ $contributor->photo ? asset($contributor->photo) : asset('images/default-avatar.png') }}"
                                                            class="w-8 h-8 rounded-full border"/>
                                                        <span>{{ $contributor->prenom }} {{ $contributor->nom ?? '' }}</span>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Deadline -->
                                            <div class="flex items-center gap-2">
                                                <img src="{{ asset('images/Calendar.png') }}" class="w-5 h-5"/>
                                                <span>Deadline</span>
                                            </div>
                                            <div>{{ $task->deadline }}</div>
                                        </div>

                                        <hr class="border-gray-300"/>

                                        <div>
                                            <h3 class="font-semibold">Description</h3>
                                            <p class="text-gray-600">{{ $task->description }}</p>
                                        </div>

                                        <hr class="border-gray-300"/>

                                        <!-- COMMENTS SECTION -->
                                        <div data-task-comments="{{ $task->id }}">
                                            <h3 class="font-semibold mb-2">Commentaires</h3>

                                            <!-- Add Comment -->
                                            <form method="POST"
                                                action="{{ route('tasks.comments.store', $task->id) }}"
                                                class="flex gap-2 items-center mb-4 js-comment-create-form"
                                                data-comment-task-id="{{ $task->id }}"
                                                data-user-name="{{ auth()->user()->prenom }}"
                                                data-user-photo="{{ auth()->user()->photo ? asset(auth()->user()->photo) : asset('images/default-avatar.png') }}">
                                                @csrf
                                                <img src="{{ auth()->user()->photo ? asset(auth()->user()->photo) : asset('images/default-avatar.png') }}" class="w-8 h-8 rounded-full"/>
                                                <input type="text" name="comment" placeholder="Ajouter un commentaire..." required
                                                    class="flex-1 border rounded-xl p-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600"/>
                                                <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded-xl hover:bg-cyan-700">Publier</button>
                                            </form>

                                            <!-- Existing Comments -->
                                            <div class="space-y-3 max-h-64 overflow-y-auto js-comment-list" data-comment-task-id="{{ $task->id }}">
                                                @foreach($task->commentaires as $comment)
                                                    <div class="flex gap-2 items-start text-sm js-comment-item" data-comment-id="{{ $comment->id }}">
                                                        <img src="{{ $comment->user->photo ? asset($comment->user->photo) : asset('images/default-avatar.png') }}" class="w-8 h-8 rounded-full"/>
                                                        <div class="flex-1">
                                                            <div class="flex items-center justify-between gap-2">
                                                                <div>
                                                                    <span class="font-semibold">{{ $comment->user->prenom }}</span>
                                                                    <span class="text-gray-400 text-xs ml-1">{{ $comment->created_at?->diffForHumans() }}</span>
                                                                </div>
                                                                @if((int) $comment->id_user === (int) auth()->id())
                                                                    <div class="flex items-center gap-2">
                                                                        <button type="button" class="text-xs text-cyan-700 hover:underline js-comment-edit-toggle">Modifier</button>
                                                                        <form method="POST"
                                                                            action="{{ route('tasks.comments.destroy', $comment->id) }}"
                                                                            class="js-comment-delete-form">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="text-xs text-red-600 hover:underline">Supprimer</button>
                                                                        </form>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <p class="text-gray-600 js-comment-text">{{ $comment->texte }}</p>

                                                            @if((int) $comment->id_user === (int) auth()->id())
                                                                <form method="POST"
                                                                    action="{{ route('tasks.comments.update', $comment->id) }}"
                                                                    class="mt-2 flex gap-2 items-center hidden js-comment-update-form">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="text" name="comment" value="{{ $comment->texte }}" required
                                                                        class="flex-1 border rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600"/>
                                                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-cyan-600 text-white text-xs hover:bg-cyan-700">Enregistrer</button>
                                                                    <button type="button" class="px-3 py-1.5 rounded-lg border text-xs js-comment-edit-cancel">Annuler</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <hr class="border-gray-300"/>

                                    <!-- X Close Icon -->
                                        <button 
                                            onclick="closeTaskModal({{ $task->id }})"
                                            class="absolute top-4 right-4 text-gray-400 hover:text-cyan-500 transition-colors"
                                            title="Close"
                                        >
                                            <!-- Simple X Icon using SVG -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>

                                    </div>
                                </div>

                                <!-- UPDATE TASK MODAL -->
                                <div id="task-update-modal-{{ $task->id }}" class="hidden fixed inset-0 z-50 overflow-auto">
                                    <div class="absolute inset-0 bg-black/40" onclick="closeUpdateModal({{ $task->id }})"></div>

                                    <div class="relative bg-white max-w-lg mx-auto mt-24 rounded-3xl shadow-xl p-6 space-y-4">
                                        <h2 class="text-2xl font-bold">Modifier Tache </h2>

                                        <form method="POST" action="{{ url('/tasks/'.$task->id) }}">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <label class="text-sm font-medium">Nom tâche</label>
                                                <input type="text" name="nom_tache" value="{{ $task->nom_tache }}" required
                                                    class="w-full border rounded-xl p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600 hover:ring-2 hover:ring-cyan-200">
                                            </div>

                                            <div>
                                                <label class="text-sm font-medium">Description</label>
                                                <textarea name="description" rows="3"
                                                        class="w-full border rounded-xl p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600 hover:ring-2 hover:ring-cyan-200">{{ $task->description }}</textarea>
                                            </div>

                                            <div>
                                                <label class="text-sm font-medium">Priorité</label>
                                                <select name="priorite" required
                                                        class="w-full border rounded-xl p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600 hover:ring-2 hover:ring-cyan-200">
                                                    <option value="low" {{ $task->priorite=='low'?'selected':'' }}>Faible</option>
                                                    <option value="medium" {{ $task->priorite=='medium'?'selected':'' }}>Moyenne</option>
                                                    <option value="high" {{ $task->priorite=='high'?'selected':'' }}>Élevée</option>
                                                </select>
                                            </div>

                                            @if($role == 4)
                                                <div>
                                                    <label class="text-sm font-medium">Statut</label>
                                                    <select name="id_etat" required
                                                            class="w-full border rounded-xl p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600 hover:ring-2 hover:ring-cyan-200">
                                                        @foreach($etats as $etat)
                                                            <option value="{{ $etat->id }}" {{ $task->id_etat == $etat->id ? 'selected' : '' }}>
                                                                {{ $etat->etat }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif

                                            <div>
                                                <label class="text-sm font-medium">Deadline</label>
                                                <input type="date" name="deadline" value="{{ $task->deadline }}" required
                                                    class="w-full border rounded-xl p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600 hover:ring-2 hover:ring-cyan-200">
                                            </div>

                                            

                                            <div class="flex justify-end gap-3 pt-4">
                                                <button type="button" onclick="closeUpdateModal({{ $task->id }})"
                                                        class="px-4 py-2 rounded-xl border hover:ring-2 hover:ring-cyan-400">Annuler</button>

                                                <button type="submit" class="px-5 py-2 rounded-xl bg-cyan-600 text-white hover:bg-cyan-700">
                                                    Enregistrer
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    function setOpenTaskInUrl(taskId) {
        const url = new URL(window.location.href);
        if (taskId) {
            url.searchParams.set('open_task', taskId);
        } else {
            url.searchParams.delete('open_task');
        }
        window.history.replaceState({}, '', url);
    }

    // TASK DETAIL MODAL
    function openTaskModal(id) {
        const modal = document.getElementById('task-modal-' + id);
        if(modal) {
            modal.classList.remove('hidden');
            setOpenTaskInUrl(id);
        }
    }

    function closeTaskModal(id) {
        const modal = document.getElementById('task-modal-' + id);
        if(modal) {
            modal.classList.add('hidden');
            setOpenTaskInUrl(null);
        }
    }

    // CONTRIBUTORS MODAL (existing)
    const contributorState = {};
    function openContributorModal(taskId) {
        const modal = document.getElementById('modal-' + taskId);
        if(!modal) return;
        modal.classList.remove('hidden');

        if (!contributorState[taskId]) {
            contributorState[taskId] = { original: new Set(), pending: new Set() };
            modal.querySelectorAll('.contributor-item.bg-cyan-100').forEach(el => {
                const userId = el.id.split('-').pop();
                contributorState[taskId].original.add(userId);
                contributorState[taskId].pending.add(userId);
            });
        }
    }

    function closeContributorModal(taskId) {
        const modal = document.getElementById('modal-' + taskId);
        if(modal) modal.classList.add('hidden');
    }

    function toggleContributor(taskId, userId) {
        const el = document.getElementById(`task-${taskId}-user-${userId}`);
        const state = contributorState[taskId];

        if(state.pending.has(String(userId))){
            state.pending.delete(String(userId));
            el.classList.remove('bg-cyan-100','border-cyan-500');
            el.classList.add('border-gray-200');
        } else {
            state.pending.add(String(userId));
            el.classList.add('bg-cyan-100','border-cyan-500');
        }
    }

    function saveContributors(taskId){
        const state = contributorState[taskId];
        const toAdd = [...state.pending].filter(id => !state.original.has(id));
        const toRemove = [...state.original].filter(id => !state.pending.has(id));
        const requests = [];

        toAdd.forEach(userId => {
            requests.push(fetch(`/tasks/${taskId}/contributor-toggle`,{
                method:'POST',
                headers:{
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },
                body: JSON.stringify({user_id:userId, assign:true})
            }));
        });

        toRemove.forEach(userId => {
            requests.push(fetch(`/tasks/${taskId}/contributor-toggle`,{
                method:'POST',
                headers:{
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },
                body: JSON.stringify({user_id:userId, assign:false})
            }));
        });

        Promise.all(requests).then(()=> {
            closeContributorModal(taskId);
            location.reload();
        });
    }

    function changePage(taskId, page){
        document.querySelectorAll(`#modal-${taskId} .contributor-item`).forEach(el => el.classList.add('hidden'));
        document.querySelectorAll(`#modal-${taskId} .page-${page}`).forEach(el => el.classList.remove('hidden'));
    }

    function openUpdateModal(id) {
        const modal = document.getElementById('task-update-modal-' + id);
        if(modal) modal.classList.remove('hidden');
    }

    function closeUpdateModal(id) {
        const modal = document.getElementById('task-update-modal-' + id);
        if(modal) modal.classList.add('hidden');
    }

 
    function openCreateTaskModal() {
        document.getElementById('task-create-modal').classList.remove('hidden');
    }

    function closeCreateTaskModal() {
        document.getElementById('task-create-modal').classList.add('hidden');
    }

    function commentItemTemplate(comment, defaultAvatar) {
        const safeText = (comment.texte || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        const safeValue = safeText.replace(/"/g, '&quot;');
        const avatar = comment.user?.photo || defaultAvatar;
        const userName = comment.user?.prenom || 'Utilisateur';
        const created = comment.created_human || 'A l\'instant';

        return `
            <div class="flex gap-2 items-start text-sm js-comment-item" data-comment-id="${comment.id}">
                <img src="${avatar}" class="w-8 h-8 rounded-full"/>
                <div class="flex-1">
                    <div class="flex items-center justify-between gap-2">
                        <div>
                            <span class="font-semibold">${userName}</span>
                            <span class="text-gray-400 text-xs ml-1">${created}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" class="text-xs text-cyan-700 hover:underline js-comment-edit-toggle">Modifier</button>
                            <form method="POST" action="${comment.urls.destroy}" class="js-comment-delete-form">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-xs text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </div>
                    </div>

                    <p class="text-gray-600 js-comment-text">${safeText}</p>

                    <form method="POST" action="${comment.urls.update}" class="mt-2 flex gap-2 items-center hidden js-comment-update-form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="text" name="comment" value="${safeValue}" required
                            class="flex-1 border rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600"/>
                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-cyan-600 text-white text-xs hover:bg-cyan-700">Enregistrer</button>
                        <button type="button" class="px-3 py-1.5 rounded-lg border text-xs js-comment-edit-cancel">Annuler</button>
                    </form>
                </div>
            </div>
        `;
    }

    async function submitCommentForm(form, options = {}) {
        const body = new FormData(form);
        const method = options.method || form.method || 'POST';
        if (options.methodOverride) {
            body.set('_method', options.methodOverride);
        }

        const response = await fetch(form.action, {
            method: method.toUpperCase() === 'GET' ? 'POST' : method,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body,
        });

        if (!response.ok) {
            throw new Error('request_failed');
        }

        return response.json();
    }

    function bindCommentActions(scope = document) {
        scope.querySelectorAll('.js-comment-edit-toggle').forEach((btn) => {
            if (btn.dataset.bound === '1') return;
            btn.dataset.bound = '1';
            btn.addEventListener('click', () => {
                const item = btn.closest('.js-comment-item');
                if (!item) return;
                item.querySelector('.js-comment-update-form')?.classList.remove('hidden');
                item.querySelector('.js-comment-text')?.classList.add('hidden');
            });
        });

        scope.querySelectorAll('.js-comment-edit-cancel').forEach((btn) => {
            if (btn.dataset.bound === '1') return;
            btn.dataset.bound = '1';
            btn.addEventListener('click', () => {
                const item = btn.closest('.js-comment-item');
                if (!item) return;
                item.querySelector('.js-comment-update-form')?.classList.add('hidden');
                item.querySelector('.js-comment-text')?.classList.remove('hidden');
            });
        });

        scope.querySelectorAll('.js-comment-create-form').forEach((form) => {
            if (form.dataset.bound === '1') return;
            form.dataset.bound = '1';
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const input = form.querySelector('input[name="comment"]');
                if (!input || !input.value.trim()) return;
                try {
                    const payload = await submitCommentForm(form);
                    const list = document.querySelector(`.js-comment-list[data-comment-task-id="${form.dataset.commentTaskId}"]`);
                    if (!list || !payload.comment) return;
                    list.insertAdjacentHTML('afterbegin', commentItemTemplate(payload.comment, form.dataset.userPhoto));
                    input.value = '';
                    bindCommentActions(list);
                } catch (_) {
                    form.submit();
                }
            });
        });

        scope.querySelectorAll('.js-comment-update-form').forEach((form) => {
            if (form.dataset.bound === '1') return;
            form.dataset.bound = '1';
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                try {
                    const payload = await submitCommentForm(form, { methodOverride: 'PUT' });
                    const item = form.closest('.js-comment-item');
                    const textEl = item?.querySelector('.js-comment-text');
                    if (textEl && payload.comment?.texte) {
                        textEl.textContent = payload.comment.texte;
                    }
                    form.classList.add('hidden');
                    textEl?.classList.remove('hidden');
                } catch (_) {
                    form.submit();
                }
            });
        });

        scope.querySelectorAll('.js-comment-delete-form').forEach((form) => {
            if (form.dataset.bound === '1') return;
            form.dataset.bound = '1';
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (!confirm('Supprimer ce commentaire ?')) return;
                try {
                    await submitCommentForm(form, { methodOverride: 'DELETE' });
                    form.closest('.js-comment-item')?.remove();
                } catch (_) {
                    form.submit();
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        bindCommentActions(document);

        const openTask = new URLSearchParams(window.location.search).get('open_task');
        if (openTask && document.getElementById('task-modal-' + openTask)) {
            openTaskModal(openTask);
        }
    });

</script>

<script>
    let draggedTaskId = null;
    let draggedFromColumn = null;

    function dragTask(e) {
        draggedTaskId = e.currentTarget.dataset.taskId;
        draggedFromColumn = e.currentTarget.closest('[data-etat-id]');
    }

    function allowDrop(e) {
        e.preventDefault();
    }

    function dropOnColumn(e) {
        e.preventDefault();
        if (!draggedTaskId) return;

        const column = e.currentTarget;
        const newEtatId = column.dataset.etatId;
        if (!newEtatId) return;

        // Find the dragged task element
        const draggedTask = document.querySelector(`[data-task-id='${draggedTaskId}']`);
        if (!draggedTask) return;

        const fromColumn = draggedFromColumn || draggedTask.closest('[data-etat-id]');
        const oldEtatId = fromColumn?.dataset?.etatId;
        if (String(oldEtatId) === String(newEtatId)) {
            draggedTaskId = null;
            draggedFromColumn = null;
            return;
        }

        // Append the task to the new column immediately
        const previousParent = draggedTask.parentElement;
        const previousNextSibling = draggedTask.nextElementSibling;
        column.appendChild(draggedTask);
        updateBoardCounters(fromColumn, column, oldEtatId, newEtatId);
        syncEmptyState(fromColumn);
        syncEmptyState(column);

        // Send the request to update backend
        fetch(`/tasks/${draggedTaskId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id_etat: newEtatId })
        })
        .then(res => {
            if (!res.ok) throw new Error();
            draggedTaskId = null;
            draggedFromColumn = null;
        })
        .catch(() => {
            alert('Could not move task');
            // Revert optimistic move if backend update fails
            if (previousParent) {
                if (previousNextSibling && previousNextSibling.parentNode === previousParent) {
                    previousParent.insertBefore(draggedTask, previousNextSibling);
                } else {
                    previousParent.appendChild(draggedTask);
                }
            }
            updateBoardCounters(column, fromColumn, newEtatId, oldEtatId);
            syncEmptyState(fromColumn);
            syncEmptyState(column);
            draggedTaskId = null;
            draggedFromColumn = null;
        });
    }

    function updateBoardCounters(fromColumn, toColumn, fromEtatId, toEtatId) {
        adjustColumnCount(fromColumn, -1);
        adjustColumnCount(toColumn, 1);
        adjustSummaryCount(fromEtatId, -1);
        adjustSummaryCount(toEtatId, 1);
    }

    function adjustColumnCount(column, delta) {
        if (!column) return;
        const countEl = column.querySelector('[data-column-count]');
        if (!countEl) return;
        const current = Number.parseInt(countEl.textContent.trim(), 10) || 0;
        countEl.textContent = Math.max(0, current + delta);
    }

    function adjustSummaryCount(etatId, delta) {
        if (!etatId) return;
        const countEl = document.querySelector(`[data-summary-count="${etatId}"]`);
        if (!countEl) return;
        const current = Number.parseInt(countEl.textContent.trim(), 10) || 0;
        countEl.textContent = Math.max(0, current + delta);
    }

    function syncEmptyState(column) {
        if (!column) return;
        const taskCards = column.querySelectorAll(':scope > [data-task-id]');
        const existingEmpty = column.querySelector(':scope > .task-empty-placeholder');

        if (taskCards.length === 0 && !existingEmpty) {
            const empty = document.createElement('div');
            empty.className = 'task-empty-placeholder rounded-xl border border-dashed border-gray-300 bg-white/70 px-4 py-6 text-center';
            empty.innerHTML = '<p class="text-gray-400 text-sm">Aucune tache dans ce statut</p>';
            column.appendChild(empty);
        } else if (taskCards.length > 0 && existingEmpty) {
            existingEmpty.remove();
        }
    }

</script>


@endsection
