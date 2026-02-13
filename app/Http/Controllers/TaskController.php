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
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->id_role;

        $projects = collect(); // default empty collection
        $selectedProject = null;

        // Base task query with relationships
        $query = Tache::with([
            'projet:id,nom_projet,id_user',
            'projet.contributors:id,prenom,nom,photo',
            'contributors:id,prenom,nom,photo',
            'etat:id,etat',
            'commentaires.user:id,prenom,nom,photo',
        ])->withCount('commentaires');

        // Task status filter
        if ($request->filter) {
            match ($request->filter) {
                'pending'  => $query->whereHas('etat', fn($q) => $q->where('etat', 'En attente')),
                'progress' => $query->whereHas('etat', fn($q) => $q->where('etat', 'En cours')),
                'done'     => $query->whereHas('etat', fn($q) => $q->where('etat', 'Terminé')),
                default    => null,
            };
        }


        // Get projects based on role
        if ($role == 3) {
            // Project owner
            $projects = Projet::where('id_user', $user->id)->get();
        } elseif ($role == 4) {
            // Contributor
            $projects = Projet::whereHas('contributors', fn($q) => $q->where('users.id', $user->id))->get();
        } elseif ($role == 2) {
            // Supervisor
            $projects = Projet::whereHas('superviseurs', fn($q) => $q->where('users.id', $user->id))->get();
        } else {
            abort(403);
        }

        // Determine selected project
        if ($request->project_id) {
            $selectedProject = $projects->firstWhere('id', $request->project_id);
        } elseif ($projects->isNotEmpty()) {
            $selectedProject = $projects->first();
        }

        

        // Filter tasks based on role
        if ($role == 3) {
            // Project owner
            $query->whereHas('projet', fn($q) => $q->where('id_user', $user->id));
        } elseif ($role == 4) {
            // Contributor
            $query->whereHas('contributors', fn($q) => $q->where('users.id', $user->id));
        } elseif ($role == 2) {
            // Supervisor: only tasks for projects they supervise
            $supervisedProjectIds = $projects->pluck('id'); // already filtered projects
            $query->whereIn('id_projet', $supervisedProjectIds);
        }

        // Further filter by selected project if any
        if ($selectedProject) {
            $query->where('id_projet', $selectedProject->id);
        }

        // Get tasks and organize by status
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

        // All task statuses
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
        $role = $user->id_role;

        // Only supervisors can add tasks
        if ($role != 2) {
            abort(403);
        }

        $request->validate([
            'nom_tache' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priorite' => 'required|string',
            'deadline' => 'required|date',
            'id_projet' => 'required|exists:projets,id',
        ]);

        $project = Projet::findOrFail($request->id_projet);

        // Supervisor can only add tasks for projects they supervise
        if (!$project->superviseurs->contains($user->id)) {
            abort(403);
        }

        // Create task
        Tache::create([
            'nom_tache' => $request->nom_tache,
            'description' => $request->description,
            'priorite' => $request->priorite,
            'deadline' => $request->deadline,
            'id_projet' => $request->id_projet,
            'id_etat' => 1, // default "En attente"
        ]);

        return redirect()->back()->with('success', 'Tâche ajoutée avec succès !');
    }

    public function update(Request $request, $id)
    {
        $task = Tache::findOrFail($id);
        $user = auth()->user();

        // Only supervisors can update tasks
        if ($user->id_role != 2) {
            abort(403);
        }

        // Supervisor can only update tasks for projects they supervise
        if (!$task->projet->superviseurs->contains($user->id)) {
            abort(403);
        }

        $task->update($request->only(['nom_tache', 'description', 'priorite', 'deadline', 'id_etat']));

        return redirect()->back()->with('success', 'Tâche mise à jour avec succès !');
    }

    public function destroy($id)
    {
        $task = Tache::findOrFail($id);
        $user = auth()->user();

        // Only supervisors can delete tasks
        if ($user->id_role != 2) {
            abort(403);
        }

        // Supervisor can only delete tasks for projects they supervise
        if (!$task->projet->superviseurs->contains($user->id)) {
            abort(403);
        }

        $task->delete();

        return redirect()->back()->with('success', 'Tâche supprimée avec succès !');
    }


    public function updateStatus(Request $request, $id)
    {
        $task = Tache::findOrFail($id);
        $user = auth()->user();

        if ($user->id_role == 2) abort(403);
        if ($user->id_role == 3 ) abort(403);
        if ($user->id_role == 4 && !$task->projet->contributors->contains($user->id)) abort(403);

        $task->id_etat = $request->id_etat;
        $task->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'task_id' => $task->id,
                'id_etat' => (int) $task->id_etat,
            ]);
        }

        return redirect()->back();
        
    }

    public function addContributor(Request $request, Tache $task)
    {
        $user = auth()->user();

        // Only supervisors
        if ($user->id_role != 2) abort(403);

        // Supervisor can only manage contributors for projects they supervise
        if (!$task->projet->superviseurs->contains($user->id)) abort(403);

        // Ensure the user to add is already a project contributor
        if (!$task->projet->contributors->contains($request->user_id)) {
            abort(403, 'Utilisateur non assigné à ce projet.');
        }

        $task->contributors()->syncWithoutDetaching([$request->user_id]);
        return back()->with('success', 'Contributeur ajouté avec succès !');
    }

    public function removeContributor(Request $request, Tache $task)
    {
        $user = auth()->user();

        if ($user->id_role != 2) abort(403);

        if (!$task->projet->superviseurs->contains($user->id)) abort(403);

        $task->contributors()->detach($request->user_id);
        return back()->with('success', 'Contributeur retiré avec succès !');
    }

    public function toggleContributor(Request $request, Tache $task)
    {
        $user = auth()->user();

        if ($user->id_role != 2) abort(403);

        if (!$task->projet->superviseurs->contains($user->id)) abort(403);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'assign' => 'required|boolean'
        ]);

        $contributorId = $request->user_id;

        // Make sure user is a project contributor
        if (!$task->projet->contributors->contains($contributorId)) {
            abort(403, 'Utilisateur non assigné à ce projet.');
        }

        if ($request->assign) {
            $task->contributors()->syncWithoutDetaching([$contributorId]);
        } else {
            $task->contributors()->detach($contributorId);
        }

        return response()->json(['success' => true]);
    }

    // Archiver une tache
    public function archiveTask(Tache $task) 
    {
        // If the task is archived, unarchive it (set to En attente, id_etat = 1)
        // Otherwise, archive it (set to Archivé, id_etat = 4)
        $task->update([
            'id_etat' => $task->id_etat == 4 ? 1 : 4
        ]);

        $message = $task->id_etat == 4 ? 'Tâche archivée !' : 'Tâche désarchivée !';

        return redirect()->back()
                        ->with('success', $message);
    }

    public function storeComment(Request $request, Tache $task)
    {
        $user = Auth::user();

        if (!$this->canAccessTaskComments($user, $task)) {
            abort(403);
        }

        $data = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        Commentaire::create([
            'texte' => $data['comment'],
            'id_user' => $user->id,
            'id_tache' => $task->id,
        ]);

        if ($request->expectsJson()) {
            $created = Commentaire::with('user:id,prenom,nom,photo')
                ->where('id_tache', $task->id)
                ->latest('id')
                ->first();

            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $created->id,
                    'texte' => $created->texte,
                    'created_human' => optional($created->created_at)->diffForHumans() ?? 'A l\'instant',
                    'is_owner' => true,
                    'user' => [
                        'prenom' => optional($created->user)->prenom ?? 'Utilisateur',
                        'photo' => optional($created->user)->photo,
                    ],
                    'urls' => [
                        'update' => route('tasks.comments.update', $created->id),
                        'destroy' => route('tasks.comments.destroy', $created->id),
                    ],
                ],
            ]);
        }

        return back()->with('success', 'Commentaire ajoute avec succes.');
    }

    public function updateComment(Request $request, Commentaire $comment)
    {
        $user = Auth::user();

        if ((int) $comment->id_user !== (int) $user->id) {
            abort(403);
        }

        $task = Tache::findOrFail($comment->id_tache);
        if (!$this->canAccessTaskComments($user, $task)) {
            abort(403);
        }

        $data = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        $comment->update([
            'texte' => $data['comment'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'texte' => $comment->texte,
                    'updated_human' => optional($comment->updated_at)->diffForHumans() ?? 'A l\'instant',
                ],
            ]);
        }

        return back()->with('success', 'Commentaire modifie avec succes.');
    }

    public function destroyComment(Commentaire $comment)
    {
        $user = Auth::user();

        if ((int) $comment->id_user !== (int) $user->id) {
            abort(403);
        }

        $task = Tache::findOrFail($comment->id_tache);
        if (!$this->canAccessTaskComments($user, $task)) {
            abort(403);
        }

        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Commentaire supprime avec succes.');
    }

    private function canAccessTaskComments($user, Tache $task): bool
    {
        $role = (int) ($user->id_role ?? 0);

        if ($role === 3) {
            return (int) optional($task->projet)->id_user === (int) $user->id;
        }

        if ($role === 2) {
            return $task->projet()
                ->whereHas('superviseurs', fn ($q) => $q->where('users.id', $user->id))
                ->exists();
        }

        if ($role === 4) {
            return $task->contributors()
                ->where('users.id', $user->id)
                ->exists();
        }

        return false;
    }



}
