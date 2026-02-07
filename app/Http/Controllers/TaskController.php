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
    /* =========================
     |  COMMENTAIRES
     ========================= */

    public function addComment(Request $request, Tache $task)
    {
        $request->validate([
            'texte' => 'required|string|max:1000',
        ]);

        $task->commentaires()->create([
            'texte'   => $request->texte,
            'user_id' => auth()->id(), // ✅ colonne correcte
        ]);

        return back()->with('success', 'Commentaire ajouté !');
    }

    public function updateComment(Request $request, Commentaire $commentaire)
    {
        abort_if($commentaire->user_id !== auth()->id(), 403);

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
        abort_if($commentaire->user_id !== auth()->id(), 403);

        $commentaire->delete();

        return back()->with('success', 'Commentaire supprimé !');
    }

    /* =========================
     |  LISTE DES TÂCHES
     ========================= */

    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->id_role;

        $projects = collect();
        $selectedProject = null;

        $query = Tache::with([
            'projet.contributeurs',
            'contributeurs',
            'etat',
            'commentaires.user'
        ]);

        /* ---------- Filtres par état ---------- */
        if ($request->filter) {
            match ($request->filter) {
                'pending'  => $query->whereHas('etat', fn ($q) => $q->where('etat', 'En attente')),
                'progress' => $query->whereHas('etat', fn ($q) => $q->where('etat', 'En cours')),
                'done'     => $query->whereHas('etat', fn ($q) => $q->where('etat', 'Terminé')),
                'archived' => $query->whereHas('etat', fn ($q) => $q->where('etat', 'Archivé')),
                default    => null,
            };
        }

        /* ---------- Projets selon le rôle ---------- */
        if ($role == 4) { // Contributeur
            $projects = Projet::whereHas('contributeurs', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })->get();

            $query->whereHas('projet.contributeurs', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });

        } elseif ($role == 2) { // Superviseur
            $projects = Projet::whereHas('superviseurs', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })->get();

            $query->whereIn('id_projet', $projects->pluck('id'));

        } elseif ($role == 3) { // Chef de projet
            $projects = Projet::where('user_id', $user->id)->get();

            $query->whereHas('projet', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        } else {
            abort(403);
        }

        /* ---------- Projet sélectionné ---------- */
        if ($request->project_id) {
            $selectedProject = $projects->firstWhere('id', $request->project_id);
        } elseif ($projects->isNotEmpty()) {
            $selectedProject = $projects->first();
        }

        if ($selectedProject) {
            $query->where('id_projet', $selectedProject->id);
        }

        /* ---------- Regroupement par état ---------- */
        $tasksRaw = $query->get();

        $tasksByStatus = collect([
            'En attente' => collect(),
            'En cours'   => collect(),
            'Terminé'    => collect(),
            'Archivé'    => collect(),
        ]);

        foreach ($tasksRaw as $task) {
            $statusName = $task->etat->etat ?? 'En attente';
            $tasksByStatus[$statusName]->push($task);
        }

        return view('tasks.index', [
            'tasks' => $tasksByStatus,
            'etats' => Etat::all(),
            'projects' => $projects,
            'selectedProject' => $selectedProject,
        ]);
    }

    /* =========================
     |  CRUD TÂCHES
     ========================= */

    public function store(Request $request)
    {
        $user = Auth::user();
        abort_if($user->id_role != 2, 403);

        $request->validate([
            'nom_tache' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priorite' => 'required|string',
            'deadline' => 'required|date',
            'id_projet' => 'required|exists:projets,id',
        ]);

        $project = Projet::findOrFail($request->id_projet);
        abort_if(!$project->superviseurs->contains($user->id), 403);

        Tache::create([
            'nom_tache' => $request->nom_tache,
            'description' => $request->description,
            'priorite' => $request->priorite,
            'deadline' => $request->deadline,
            'id_projet' => $project->id,
            'id_etat' => 1,
        ]);

        return back()->with('success', 'Tâche ajoutée avec succès !');
    }

    public function update(Request $request, $id)
    {
        $task = Tache::findOrFail($id);
        $user = Auth::user();

        abort_if($user->id_role != 2, 403);
        abort_if(!$task->projet->superviseurs->contains($user->id), 403);

        $task->update($request->only([
            'nom_tache',
            'description',
            'priorite',
            'deadline',
            'id_etat'
        ]));

        return back()->with('success', 'Tâche mise à jour avec succès !');
    }

    public function destroy($id)
    {
        $task = Tache::findOrFail($id);
        $user = Auth::user();

        abort_if($user->id_role != 2, 403);
        abort_if(!$task->projet->superviseurs->contains($user->id), 403);

        $task->delete();

        return back()->with('success', 'Tâche supprimée avec succès !');
    }

    /* =========================
     |  CONTRIBUTEURS TÂCHE
     ========================= */

    public function toggleContributeurs(Request $request, Tache $task)
    {
        $user = Auth::user();

        abort_if($user->id_role != 2, 403);
        abort_if(!$task->projet->superviseurs->contains($user->id), 403);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'assign' => 'required|boolean',
        ]);

        abort_if(
            !$task->projet->contributeurs->contains($request->user_id),
            403,
            'Utilisateur non assigné au projet'
        );

        if ($request->assign) {
            $task->contributeurs()->syncWithoutDetaching([$request->user_id]);
        } else {
            $task->contributeurs()->detach($request->user_id);
        }

        return response()->json(['success' => true]);
    }

    /* =========================
     |  ARCHIVAGE
     ========================= */

    public function archiveTask(Tache $task)
    {
        $task->update([
            'id_etat' => $task->id_etat == 4 ? 1 : 4
        ]);

        return redirect()
            ->route('tasks.index')
            ->with('success', $task->id_etat == 4 ? 'Tâche archivée !' : 'Tâche désarchivée !');
    }
}
