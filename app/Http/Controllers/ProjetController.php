<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use App\Models\User;
use Illuminate\Http\Request;

class ProjetController extends Controller
{


    // Liste des projets
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $user = auth()->user();
        $role = $user->id_role;

        $query = Projet::with(['etat', 'superviseurs', 'contributeurs', 'taches']);

        if ($role == 3) {
            // Owner / chef de projet
            $query->where('id_user', $user->id);
        } elseif ($role == 2) {
            // Supervisor: projects they supervise
            $query->whereHas('superviseurs', fn($q) => $q->where('users.id', $user->id));
        } elseif ($role == 4) {
            // Contributor: projects they contribute to
            $query->whereHas('contributeurs', fn($q) => $q->where('users.id', $user->id));
        } else {
            abort(403);
        }

        if ($filter === 'favorites') {
            $query->where('is_favorite', 1);
        }

        if ($filter === 'archived') {
            $query->where('id_etat', 4);
        }

        $projects = $query->get();

        $users = User::all();

        $contributors = User::whereHas('role', fn ($q) =>
            $q->where('role', 'Contributeur')
        )->get();

        $userRole = auth()->user()->id_role;

        if ($userRole == 2) { // Supervisor
            $assignables = User::where('id_role', 4)->get(); // Contributors
        } elseif ($userRole == 3) { // Chef de Projet
            $assignables = User::where('id_role', 2)->get(); // Supervisors
        } else {
            $assignables = collect();
        }

        return view('projects.index', compact('projects', 'filter', 'users', 'assignables', 'userRole'));

    }





    // Ajouter des contributeurs

    public function toggleContributor(Request $request, Projet $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'assign' => 'required|boolean'
        ]);

        $userId = $request->input('user_id');
        $assign = $request->input('assign');

        $user = User::findOrFail($userId);

        if ($user->role->role !== 'Contributeur') {
            return response()->json(['error' => 'Not a contributor'], 403);
        }

        if ($assign) {
            $project->contributors()->syncWithoutDetaching([$userId]);
        } else {
            $project->contributors()->detach($userId);
        }

        return response()->json(['success' => true]);
    }

    public function toggleSupervisor(Request $request, Projet $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'assign' => 'required|boolean'
        ]);

        $userId = $request->input('user_id');
        $user = User::findOrFail($userId);

        if ($user->role->role !== 'Superviseur') {
            return response()->json(['error' => 'Not a supervisor'], 403);
        }

        if ($assign = $request->input('assign')) {
            $project->superviseurs()->syncWithoutDetaching([$userId]);
        } else {
            $project->superviseurs()->detach($userId);
        }

        return response()->json(['success' => true]);
    }



    public function addContributor(Request $request, Projet $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // Only allow contributors
        if ($user->role->role !== 'Contributeur') {
            return back()->withErrors('Cet utilisateur n’est pas un contributeur.');
        }

        $project->contributors()->syncWithoutDetaching([$user->id]);

        return back()->with('success', 'Contributeur ajouté.');
    }

    // Ajouter un superviseur

     public function addSupervisor(Request $request, Projet $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $userId = $request->input('user_id');

        if ($userId == $project->user_id) {
            return back()->with('error', 'Le chef de projet ne peut pas être ajouté comme superviseur.');
        }

        $project->superviseurs()->syncWithoutDetaching([$userId]);

        return back()->with('success', 'Superviseur ajouté avec succès.');
    }




    // Formulaire création
    public function create()
    {
         $users = User::all();
        $superviseurs = User::whereHas('role', fn($q) => $q->where('role','Superviseur'))->get();
        return view('projects.form', compact('superviseurs','users'));
    }

    // Formulaire édition
    public function edit(Projet $project)
    {
        $superviseurs = User::whereHas('role', fn($q) => $q->where('role','Superviseur'))->get();
        return view('projects.form', compact('project', 'superviseurs'));
    }



    // Enregistrer un projet
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'nom_projet' => 'required|unique:projets,nom_projet',
            'description'=> 'nullable|string',
            'date_debut' => 'required|date',
            'deadline'   => 'required|date|after_or_equal:date_debut',
            'id_user'    => 'required|exists:users,id', // chef de projet ( owner )
        ]);

        // Make sure selected user is a supervisor
        $superviseur = User::where('id', $validated['id_user'])
                            ->whereHas('role', fn($q) => $q->where('role', 'Superviseur'))
                            ->first();

        if (!$superviseur) {
            return back()->withErrors(['id_user' => 'L’utilisateur assigné doit être un superviseur.'])
                        ->withInput();
        }

        // Create project (owner = logged-in user)
        $project = Projet::create([
            'nom_projet' => $validated['nom_projet'],
            'description'=> $validated['description'],
            'date_debut' => $validated['date_debut'],
            'deadline'   => $validated['deadline'],
            'id_user'    => auth()->id(), // owner / chef de projet
            'id_etat'    => 1, // default: En Attente
        ]);

        // Attach the supervisor to pivot table
        $project->superviseurs()->attach($superviseur->id);

        return redirect()->route('projects.index')
                        ->with('success', 'Projet créé avec succès !');
    }




    // Mettre à jour un projet
    public function update(Request $request, Projet $project)
    {
        $validated = $request->validate([
            'nom_projet' => 'required|unique:projets,nom_projet,' . $project->id,
            'deadline'   => 'required|date',
            'date_debut' => 'required|date',
            'id_etat'    => 'required|in:1,2,3,4',
            'id_user'    => 'required|exists:users,id',
        ]);

        $superviseur = User::where('id', $validated['id_user'])
                           ->whereHas('role', fn($q) => $q->where('role', 'Superviseur'))
                           ->first();

        if (!$superviseur) {
            return back()->withErrors(['id_user' => 'L’utilisateur assigné doit être un superviseur.'])
                         ->withInput();
        }

        $project->update([
            'nom_projet' => $validated['nom_projet'],
            'description'=> $request->description,
            'date_debut' => $validated['date_debut'],
            'deadline'   => $validated['deadline'],
            'id_etat'    => $validated['id_etat'],
        ]);


        // Sync supervisor pivot
        $project->superviseurs()->sync([$validated['id_user']]);

        return redirect()->route('projects.index')
                         ->with('success', 'Projet mis à jour avec succès !');
    }

    // Supprimer un projet
    public function destroy(Projet $project)
    {
        $project->delete();
        return redirect()->route('projects.index')
                         ->with('success', 'Projet supprimé !');
    }

    // Archiver un projet
    public function archive(Projet $project)
    {
        $project->update(['id_etat' => 4]);
        return redirect()->route('projects.index')
                         ->with('success', 'Projet archivé !');
    }

    // Toggle favorite
    public function toggleFavorite(Projet $project)
    {
        $user = auth()->user();

        // Authorization: same visibility rules as index
        if (
            ($user->id_role == 3 && $project->id_user != $user->id) ||
            ($user->id_role == 2 && !$project->superviseurs->contains($user->id)) ||
            ($user->id_role == 4 && !$project->contributors->contains($user->id))
        ) {
            abort(403);
        }

        $project->update([
            'is_favorite' => !$project->is_favorite
        ]);

        return redirect()->back();
    }
}

