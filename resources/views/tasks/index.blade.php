@php
    $layout = auth()->user()->id_role === 3
        ? 'layouts.chef_layout'
        : 'layouts.superviseur_layout';
@endphp

@extends($layout)

@section('title', 'Taches')
@section('page-title', 'Taches')

@section('content')

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
@endphp

<div class="p-6 space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6 h-[80px] bg-white rounded-3xl shadow-md px-6">
        <h1 class="text-xl font-semibold">
            @if($selectedProject && $projects->isNotEmpty()) 
                <span> {{ $selectedProject->nom_projet }}</span>
            @endif
        </h1>

        <div class="flex items-center gap-3">
            @if($projects->isNotEmpty())
            <form method="GET">
                <select name="project_id" onchange="this.form.submit()"
                    class="min-w-[200px] px-4 py-2 rounded-xl border border-cyan-300 text-sm bg-white text-gray-700
                    focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500">
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}"
                            {{ $selectedProject && $selectedProject->id == $project->id ? 'selected' : '' }}>
                            {{ $project->nom_projet }}
                        </option>
                    @endforeach
                </select>
            </form>
            @endif
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

        <!-- CREATE TASK MODAL -->
        <div id="task-create-modal" class="hidden fixed inset-0 z-50 overflow-auto">
            <!-- Dark overlay -->
            <div class="absolute inset-0 bg-black/40" onclick="closeCreateTaskModal()"></div>

                <!-- Modal content -->
                <div class="relative bg-white max-w-lg mx-auto mt-24 rounded-3xl shadow-xl p-6 space-y-4">
                    <h2 class="text-2xl font-bold">Créer une tâche</h2>

                    <form method="POST" action="{{ url(route('tasks.store')) }}">
                    @csrf

                    <input type="hidden" name="id_projet" value="{{ $selectedProject->id }}">


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
        </div>

        <!-- TASKS LIST -->
        @if($selectedProject && $tasks->isNotEmpty())
            <div class="flex gap-6 overflow-x-auto pb-4 z-0">

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
                            bg-gray-50 rounded-2xl p-4 shadow
                            {{ $taskFilter === 'all' ? 'flex-shrink-0 w-80' : 'flex-1' }}
                        "
                        data-etat-id="{{ $etats->firstWhere('etat', $statusName)->id }}"
                        ondragover="allowDrop(event)"
                        ondrop="dropOnColumn(event)"
                    >

                        <!-- HEADER -->
                        <div class="flex items-center gap-3 mb-3">
                            <span class="w-3 h-3 rounded-full {{ $style['dot'] }}"></span>
                            <h2 class="font-semibold text-base {{ $style['text'] }}">
                                {{ $statusName }}
                            </h2>
                        </div>

                        <div class="h-[2px] rounded-full bg-gradient-to-r {{ $style['line'] }}"></div>
                        <br>

                        @php $tasksForStatus = $tasks->get($statusName) ?? collect(); @endphp

                        @if($tasksForStatus->isEmpty())
                            <p class="text-gray-400 text-sm">Aucune tâche dans ce statut</p>
                        @else
                            @foreach($tasksForStatus as $task)
                                {{-- 🔽 YOUR EXISTING TASK CARD CODE (UNCHANGED) --}}
                                {{-- NOTHING REMOVED --}}

                                <div class="relative bg-white rounded-xl p-4 mb-4 shadow flex flex-col justify-between hover:shadow-xl hover:scale-[1.02] transition-transform duration-200 cursor-pointer z-0"
                                        draggable="{{ $role == 4 && $task->projet->contributors->contains(auth()->user()->id) ? 'true' : 'false' }}"
                                        data-task-id="{{ $task->id }}"
                                        ondragstart="dragTask(event)">
                                                            


                                    <!-- Add a small "⋯" menu -->
                                    <div class="absolute top-3 right-3" x-data="{ openMenu: false }">
                                        <button @click="openMenu = !openMenu"
                                            class="p-1 hover:bg-gray-100 rounded transition-all"
                                            title="Options"
                                        >
                                            <!-- Three dots icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v.01M12 12v.01M12 18v.01" />
                                            </svg>
                                        </button>

                                        <!-- Dropdown menu -->
                                        <div x-show="openMenu" @click.outside="openMenu = false" x-transition
                                            class="absolute right-0 mt-2 w-36 bg-white border rounded-xl shadow-lg z-50 "
                                        >
                                            <button @click="openTaskModal({{ $task->id }}); openMenu=false"
                                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 text-sm">
                                                Ouvrir tâche
                                            </button>

                                            @if($role == 2)
                                                <!-- Divider -->
                                                <div class="border-t my-1"></div>

                                                
                                                
                                                <button @click="openUpdateModal({{ $task->id }}); openMenu=false"
                                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 text-sm">
                                                    Modifier tâche
                                                </button>

                                                <!-- Divider -->
                                                <div class="border-t my-1"></div>


                                                <form method="POST" action="{{ route('tasks.archive', $task->id) }}">
                                                    @csrf
                                                    <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 openMenu=false">
                                                        {{ $task->id_etat == 4 ? '♻️ Désarchiver' : '📦 Archiver' }}
                                                    </button>
                                                </form>


                                                 <!-- Divider -->
                                                <div class="border-t my-1"></div>

                                                <!-- Delete -->
                                                <form method="POST" action="{{ route('tasks.destroy', $task->id) }}"
                                                    onsubmit="return confirm('Delete this task?')">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="w-full flex items-center gap-3 px-4 py-2 text-sm
                                                            text-red-600 hover:bg-red-50 rounded-lg transition"
                                                        @click="openMenu=false">
                                                        🗑️ Supprimer tâche
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
                                        <span>{{ $task->commentaires->count() }} commentaires</span>
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
                                                    w-[420px] p-6">

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
                                        <div>
                                            <h3 class="font-semibold mb-2">Commentaires</h3>

                                            <!-- Add Comment -->
                                            <form method="POST" action="" class="flex gap-2 items-center mb-4">
                                                @csrf
                                                <img src="{{ auth()->user()->photo ? asset(auth()->user()->photo) : asset('images/default-avatar.png') }}" class="w-8 h-8 rounded-full"/>
                                                <input type="text" name="comment" placeholder="Ajouter un commentaire..." required
                                                    class="flex-1 border rounded-xl p-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600"/>
                                                <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded-xl hover:bg-cyan-700">Publier</button>
                                            </form>

                                            <!-- Existing Comments -->
                                            <div class="space-y-3 max-h-64 overflow-y-auto">
                                                @foreach($task->commentaires as $comment)
                                                    <div class="flex gap-2 items-start text-sm">
                                                        <img src="{{ $comment->user->photo ? asset($comment->user->photo) : asset('images/default-avatar.png') }}" class="w-8 h-8 rounded-full"/>
                                                        <div>
                                                            <span class="font-semibold">{{ $comment->user->prenom }}</span>
                                                            <span class="text-gray-400 text-xs ml-1">{{ $comment->created_at->diffForHumans() }}</span>
                                                            <p class="text-gray-600">{{ $comment->texte }}</p>
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
                            .
                        @endif
                    </div>

                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    // TASK DETAIL MODAL
    function openTaskModal(id) {
        const modal = document.getElementById('task-modal-' + id);
        if(modal) modal.classList.remove('hidden');
    }

    function closeTaskModal(id) {
        const modal = document.getElementById('task-modal-' + id);
        if(modal) modal.classList.add('hidden');
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



    
</script>

<script>
    let draggedTaskId = null;

    function dragTask(e) {
        draggedTaskId = e.currentTarget.dataset.taskId;
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

        // Append the task to the new column immediately
        column.appendChild(draggedTask);

        // Send the request to update backend
        fetch(`/tasks/${draggedTaskId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id_etat: newEtatId })
        })
        .then(res => {
            if (!res.ok) throw new Error();
            // optionally show a small success message
        })
        .catch(() => {
            alert('Could not move task');
            // revert back if error
            location.reload();
        });
    }

</script>


@endsection
