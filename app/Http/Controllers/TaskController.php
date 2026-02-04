<?php

namespace App\Http\Controllers;

use App\Models\Tache;
use App\Models\Projet;
use App\Models\Etat;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function addComment(Request $request, Tache $task)
    {
        $request->validate([
            'texte' => 'required|string|max:1000',
        ]);

        $task->commentaires()->create([
            'texte'   => $request->texte,
            'id_user' => auth()->id(),
        ]);

        return back()->with('success', 'Commentaire ajouté !');
    }

    public function updateComment(Request $request, Commentaire $commentaire)
    {
        abort_if($commentaire->id_user !== auth()->id(), 403);

        $request->validate([
            'texte' => 'required|string|max:1000',
        ]);

        $commentaire->update([
            'texte' => $request->texte,
        ]);

        return back()->with('success', 'Commentaire modifié !');
    }

    public function deleteComment(Commentaire $commentaire)
    {
        abort_if($commentaire->id_user !== auth()->id(), 403);

        $commentaire->delete();

        return back()->with('success', 'Commentaire supprimé !');
    }


    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->id_role;

        $projects = collect();
        $selectedProject = null;

        $query = Tache::with(['projet.contributeur', 'contributeur', 'etat', 'commentaires.user']);

        if ($request->filter) {
            match ($request->filter) {
                'pending'  => $query->whereHas('etat', fn($q) => $q->where('etat', 'En attente')),
                'progress' => $query->whereHas('etat', fn($q) => $q->where('etat', 'En cours')),
                'done'     => $query->whereHas('etat', fn($q) => $q->where('etat', 'Terminé')),
                'archived' => $query->whereHas('etat', fn($q) => $q->where('etat', 'Archivé')),
                default    => null,
            };
        }

        // Projects based on role
        if ($role == 4) { // Contributeur
    $projects = Projet::whereHas('contributeurs', function ($q) use ($user) {
        $q->where('users.id', $user->id);
    })->get();
} elseif ($role == 2) { // Superviseur
    $projects = Projet::whereHas('superviseurs', function ($q) use ($user) {
        $q->where('users.id', $user->id);
    })->get();
} else {
    abort(403);
}


        if ($request->project_id) {
            $selectedProject = $projects->firstWhere('id', $request->project_id);
        } elseif ($projects->isNotEmpty()) {
            $selectedProject = $projects->first();
        }

        // Filter tasks by role
        if ($role == 3) {
    // Chef : tâches dont le projet appartient à l'utilisateur
    $query->whereHas('projet', fn($q) => $q->where('id_user', $user->id));
} elseif ($role == 4) {
    // Contributeur : tâches dont le projet a cet utilisateur comme contributeur
    $query->whereHas('projet.contributeurs', fn($q) => $q->where('users.id', $user->id));
} elseif ($role == 2) {
    // Superviseur : tâches des projets supervisés
    $query->whereIn('id_projet', $projects->pluck('id'));
}


        if ($selectedProject) {
            $query->where('id_projet', $selectedProject->id);
        }

        $tasksRaw = $query->get();
        $tasksByStatus = collect([
            'En attente' => collect(),
            'En cours' => collect(),
            'Terminé' => collect(),
            'Archivé' => collect(),
        ]);

        foreach ($tasksRaw as $task) {
            $statusName = $task->etat->etat ?? 'En attente';
            $tasksByStatus[$statusName]->push($task);
        }

        $etats = Etat::all();

        return view('tasks.index', [
            'tasks' => $tasksByStatus,
            'etats' => $etats,
            'projects' => $projects,
            'selectedProject' => $selectedProject,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->id_role != 2) abort(403);

        $request->validate([
            'nom_tache' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priorite' => 'required|string',
            'deadline' => 'required|date',
            'id_projet' => 'required|exists:projets,id',
        ]);

        $project = Projet::findOrFail($request->id_projet);
        if (!$project->superviseurs->contains($user->id)) abort(403);

        Tache::create([
            'nom_tache' => $request->nom_tache,
            'description' => $request->description,
            'priorite' => $request->priorite,
            'deadline' => $request->deadline,
            'id_projet' => $request->id_projet,
            'id_etat' => 1,
        ]);

        return redirect()->back()->with('success', 'Tâche ajoutée avec succès !');
    }

    public function update(Request $request, $id)
    {
        $task = Tache::findOrFail($id);
        $user = auth()->user();
        if ($user->id_role != 2) abort(403);
        if (!$task->projet->superviseurs->contains($user->id)) abort(403);

        $task->update($request->only(['nom_tache', 'description', 'priorite', 'deadline', 'id_etat']));

        return redirect()->back()->with('success', 'Tâche mise à jour avec succès !');
    }

    public function destroy($id)
    {
        $task = Tache::findOrFail($id);
        $user = auth()->user();
        if ($user->id_role != 2) abort(403);
        if (!$task->projet->superviseurs->contains($user->id)) abort(403);

        $task->delete();
        return redirect()->back()->with('success', 'Tâche supprimée avec succès !');
    }

    public function updateStatus(Request $request, $id)
    {
        $task = Tache::findOrFail($id);
        $user = auth()->user();

        if ($user->id_role == 2 || $user->id_role == 3) abort(403);
        if ($user->id_role == 4 && !$task->projet->contributeur->contains($user->id)) abort(403);

        $task->id_etat = $request->id_etat;
        $task->save();

        return redirect()->back();
    }

    public function addContributeur(Request $request, Tache $task)
    {
        $user = auth()->user();
        if ($user->id_role != 2) abort(403);
        if (!$task->projet->superviseurs->contains($user->id)) abort(403);
        if (!$task->projet->contributeur->contains($request->user_id)) abort(403, 'Utilisateur non assigné au projet');

        $task->Contributeur()->syncWithoutDetaching([$request->user_id]);
        return back()->with('success', 'Contributeur ajouté !');
    }

    public function removeContributeur(Request $request, Tache $task)
    {
        $user = auth()->user();
        if ($user->id_role != 2) abort(403);
        if (!$task->projet->superviseurs->contains($user->id)) abort(403);

        $task->contributeur()->detach($request->user_id);
        return back()->with('success', 'Contributeur retiré !');
    }

    public function toggleContributeur(Request $request, Tache $task)
    {
        $user = auth()->user();
        if ($user->id_role != 2) abort(403);
        if (!$task->projet->superviseurs->contains($user->id)) abort(403);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'assign' => 'required|boolean',
        ]);

        if (!$task->projet->contributeur->contains($request->user_id)) {
            abort(403, 'Utilisateur non assigné au projet');
        }

        if ($request->assign) {
            $task->contributeur()->syncWithoutDetaching([$request->user_id]);
        } else {
            $task->contributeur()->detach($request->user_id);
        }

        return response()->json(['success' => true]);
    }

    public function archiveTask(Tache $task)
    {
        $task->update(['id_etat' => $task->id_etat == 4 ? 1 : 4]);
        $message = $task->id_etat == 4 ? 'Tâche archivée !' : 'Tâche désarchivée !';
        return redirect()->route('tasks.index')->with('success', $message);
    }
}
