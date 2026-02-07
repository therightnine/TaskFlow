@php
    $layout = auth()->user()->id_role === 4
        ? 'layouts.superviseur_layout'
        : 'layouts.contributeur_layout';
@endphp

@extends($layout)
@section('title', 'Tâches')
@section('page-title', 'Tâches')
@section('content')

@php
    $role = auth()->user()->id_role;
    $taskFilter = request('filter', 'all'); // default = all

    $filterToStatus = [
        'pending'  => 'En attente',
        'progress' => 'En cours',
        'done'     => 'Terminé',
        'archived' => 'Archivé',
    ];

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

<div class="p-6 space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6 h-[80px] bg-white rounded-3xl shadow-md px-6">
        <h1 class="text-xl font-semibold">
            @if($selectedProject && $projects->isNotEmpty())
                <span>{{ $selectedProject->nom_projet }}</span>
            @endif
        </h1>
        <div class="flex items-center gap-3">
            @if(!empty($projects) && $projects->isNotEmpty())
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
            @foreach(['all'=>'Tous','pending'=>'En attente','progress'=>'En cours','done'=>'Terminé','archived'=>'Archivé'] as $filterKey => $filterLabel)
                <a href="{{ request()->fullUrlWithQuery(['filter' => $filterKey]) }}"
                    class="flex items-center gap-2 pb-2 text-sm font-medium
                        {{ $taskFilter==$filterKey ? 'text-cyan-600 border-b-2 border-cyan-600' : 'text-gray-500 hover:text-cyan-600' }}">
                    <span class="inline-block rounded-full {{ $taskFilter==$filterKey ? 'bg-cyan-100' : '' }} p-1">
                        <img src="{{ asset('images/ic_'.$filterKey.'.png') }}" class="w-5 h-5">
                    </span>
                    {{ $filterLabel }}
                </a>
            @endforeach

            @if($role == 2)
            <button onclick="openCreateTaskModal()"
                class="ml-auto px-4 py-2 bg-cyan-500 text-white rounded-xl hover:bg-cyan-600 transition">
                + Nouveau Tâche
            </button>
            @endif
        </div>

        <!-- CREATE TASK MODAL -->
        @if($selectedProject)
            <div id="task-create-modal" class="hidden fixed inset-0 z-50 overflow-auto">
                <div class="absolute inset-0 bg-black/40" onclick="closeCreateTaskModal()"></div>
                <div class="relative bg-white max-w-lg mx-auto mt-24 rounded-3xl shadow-xl p-6 space-y-4">
                    <h2 class="text-2xl font-bold">Créer une tâche</h2>
                    <form method="POST" action="{{ route('tasks.store') }}">
                        @csrf
                        <input type="hidden" name="id_projet" value="{{ $selectedProject->id }}">

                        <div>
                            <label class="text-sm font-medium">Nom tâche</label>
                            <input type="text" name="nom_tache" required
                                class="w-full border rounded-xl p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600 hover:ring-2 hover:ring-cyan-200">
                        </div>

                        <div>
                            <label class="text-sm font-medium">Description</label>
                            <textarea name="description" rows="3"
                                class="w-full border rounded-xl p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600 hover:ring-2 hover:ring-cyan-200"></textarea>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Priorité</label>
                            <select name="priorite" required
                                class="w-full border rounded-xl p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600 hover:ring-2 hover:ring-cyan-200">
                                <option value="low">Faible</option>
                                <option value="medium">Moyen</option>
                                <option value="high">Élevé</option>
                            </select>
                        </div>

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

                        <div>
                            <label class="text-sm font-medium">Deadline</label>
                            <input type="date" name="deadline" required
                                class="w-full border rounded-xl p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-600 hover:ring-2 hover:ring-cyan-200">
                        </div>

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
        @else
            <div class="p-4 bg-yellow-100 text-yellow-800 rounded-xl mb-4">
                Veuillez sélectionner un projet avant de créer une tâche.
            </div>
        @endif

        <!-- TASKS LIST -->
        @if($selectedProject && !empty($tasks))
            <div class="flex gap-6 overflow-x-auto pb-4 z-0">
                @foreach($statuses as $statusName => $style)
                    @php
                        $tasksForStatus = $tasks->get($statusName) ?? collect();
                        if($taskFilter !== 'all' && ($filterToStatus[$taskFilter] ?? null) !== $statusName) continue;
                    @endphp

                    <div class="bg-gray-50 rounded-2xl p-4 shadow {{ $taskFilter === 'all' ? 'flex-shrink-0 w-80' : 'flex-1' }}"
                         data-etat-id="{{ optional($etats->firstWhere('etat', $statusName))->id }}"
                         ondragover="allowDrop(event)"
                         ondrop="dropOnColumn(event)">

                        <!-- STATUS HEADER -->
                        <div class="flex items-center gap-3 mb-3">
                            <span class="w-3 h-3 rounded-full {{ $style['dot'] }}"></span>
                            <h2 class="font-semibold text-base {{ $style['text'] }}">
                                {{ $statusName }}
                            </h2>
                        </div>
                        <div class="h-[2px] rounded-full bg-gradient-to-r {{ $style['line'] }}"></div>
                        <br>

                        @if($tasksForStatus->isEmpty())
                            <p class="text-gray-400 text-sm">Aucune tâche dans ce statut</p>
                        @else
                           @foreach($tasksForStatus as $task)
    @include('tasks.partials.task_card', ['task' => $task, 'role' => $role, 'etats' => $etats])
@endforeach

                        @endif

                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- ... tout ton HTML ... -->

<!-- SCRIPTS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const commentInput = document.getElementById('new-comment-input');
    const sendBtn = document.getElementById('send-btn');
    if (commentInput && sendBtn) {
        function toggleSendButton() {
            sendBtn.classList.toggle('hidden', !commentInput.value.trim());
        }
        commentInput.addEventListener('input', toggleSendButton);
        toggleSendButton();
    }
});

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
    const draggedTask = document.querySelector(`[data-task-id='${draggedTaskId}']`);
    if (!draggedTask) return;
    column.appendChild(draggedTask);
    fetch(`/tasks/${draggedTaskId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body: JSON.stringify({ id_etat: newEtatId })
    }).then(res => { if (!res.ok) throw new Error(); })
      .catch(() => location.reload());
});

// Modals create/update
function openCreateTaskModal() { document.getElementById('task-create-modal')?.classList.remove('hidden'); }
function closeCreateTaskModal() { document.getElementById('task-create-modal')?.classList.add('hidden'); }
function openUpdateModal(id) { document.getElementById('task-update-modal-' + id)?.classList.remove('hidden'); }
function closeUpdateModal(id) { document.getElementById('task-update-modal-' + id)?.classList.add('hidden'); }
function openTaskModal(id) { document.getElementById('task-modal-' + id)?.classList.remove('hidden'); }
function closeTaskModal(id) { document.getElementById('task-modal-' + id)?.classList.add('hidden'); }

// Contributor modal
const contributeurState = {};
function openContributeurModal(taskId) {
    const modal = document.getElementById('modal-' + taskId);
    if(!modal) return;
    modal.classList.remove('hidden');
    if(!contributeurState[taskId]){
        contributeurState[taskId] = {original: new Set(), pending: new Set()};
        modal.querySelectorAll('.contributeur-item.bg-cyan-100').forEach(el => {
            const userId = el.id.split('-').pop();
            contributeurState[taskId].original.add(userId);
            contributeurState[taskId].pending.add(userId);
        });
    }
}
function closeContributeurModal(taskId){ document.getElementById('modal-' + taskId)?.classList.add('hidden'); }
function toggleContributeur(taskId, userId){
    const el = document.getElementById(`task-${taskId}-user-${userId}`);
    const state = contributeurState[taskId];
    if(state.pending.has(String(userId))){
        state.pending.delete(String(userId));
        el.classList.remove('bg-cyan-100','border-cyan-500'); el.classList.add('border-gray-200');
    } else { state.pending.add(String(userId)); el.classList.add('bg-cyan-100','border-cyan-500'); }
}
function saveContributors(taskId){
    const state = contributeurState[taskId];
    const toAdd = [...state.pending].filter(id => !state.original.has(id));
    const toRemove = [...state.original].filter(id => !state.pending.has(id));
    const requests = [];
    toAdd.forEach(userId => { requests.push(fetch(`/tasks/${taskId}/contributeur-toggle`,{
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({user_id:userId, assign:true})
    })); });
    toRemove.forEach(userId => { requests.push(fetch(`/tasks/${taskId}/contributeur-toggle`,{
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({user_id:userId, assign:false})
    })); });
    Promise.all(requests).then(()=>{ location.reload(); });
}
function changePage(taskId, page){
    document.querySelectorAll(`#modal-${taskId} .contributeur-item`).forEach(el=>el.classList.add('hidden'));
    document.querySelectorAll(`#modal-${taskId} .page-${page}`).forEach(el=>el.classList.remove('hidden'));
}
</script>

@endsection




