<div class="bg-white rounded-lg shadow p-4 mb-3 border border-gray-200">

    {{-- Titre + priorité --}}
    <div class="flex justify-between items-center">
        <h3 class="font-semibold text-gray-800">
            {{ $task->nom_tache }}
        </h3>

        <span class="text-xs px-2 py-1 rounded
            @if($task->priorite === 'Haute') bg-red-100 text-red-700
            @elseif($task->priorite === 'Moyenne') bg-yellow-100 text-yellow-700
            @else bg-green-100 text-green-700
            @endif">
            {{ $task->priorite }}
        </span>
    </div>

    {{-- Description --}}
    @if($task->description)
        <p class="text-sm text-gray-600 mt-2">
            {{ $task->description }}
        </p>
    @endif

    {{-- Infos --}}
    <div class="flex flex-wrap gap-4 text-xs text-gray-500 mt-3">
        <span>📁 {{ $task->projet->nom_projet ?? 'Projet inconnu' }}</span>
        <span>⏰ {{ \Carbon\Carbon::parse($task->deadline)->format('d/m/Y') }}</span>
        <span>📌 {{ $task->etat->etat ?? 'En attente' }}</span>
    </div>

    {{-- Actions selon rôle --}}
    <div class="mt-3 flex gap-2">

        {{-- Contributeur : changer état --}}
        @if($role == 4)
            <form method="POST" action="{{ route('tasks.updateStatus', $task->id) }}">
                @csrf
                <select name="id_etat" onchange="this.form.submit()"
                        class="text-xs border rounded px-2 py-1">
                    @foreach($etats as $etat)
                        <option value="{{ $etat->id }}"
                            @selected($task->id_etat == $etat->id)>
                            {{ $etat->etat }}
                        </option>
                    @endforeach
                </select>
            </form>
        @endif

        {{-- Superviseur : éditer / supprimer --}}
        @if($role == 2)
            <a href="#"
               class="text-xs text-blue-600 hover:underline">
                ✏️ Modifier
            </a>

            <form method="POST"
                  action="{{ route('tasks.destroy', $task->id) }}"
                  onsubmit="return confirm('Supprimer cette tâche ?')">
                @csrf
                @method('DELETE')
                <button class="text-xs text-red-600 hover:underline">
                    🗑️ Supprimer
                </button>
            </form>
        @endif
    </div>

    {{-- Commentaires --}}
    <div class="mt-4 border-t pt-3">
        <p class="text-xs font-semibold text-gray-600 mb-2">
            💬 Commentaires ({{ $task->commentaires->count() }})
        </p>

        @foreach($task->commentaires as $commentaire)
            <div class="text-xs text-gray-700 mb-1">
                <strong>{{ $commentaire->user->name ?? 'Utilisateur' }} :</strong>
                {{ $commentaire->texte }}
            </div>
        @endforeach
    </div>

</div>
