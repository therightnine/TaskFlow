<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Abonnement; // Assure-toi que ce modèle existe
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\UserAbonnement;
use function Symfony\Component\String\u;

class AdminUsersController extends Controller
{
    public function index()
    {
        // On charge les relations utiles (role, abonnement si dispo)
        $users = User::with(['role'])->get();

        $roles = Role::orderBy('libelle')->get();
        $abonnements = Abonnement::orderBy('nom')->get();

        return view('admin.utilisateurs.gest_utilisateurs', compact('users', 'roles', 'abonnements'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nom'           => ['required', 'string', 'max:100'],
            'prenom'        => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'id_role'       => ['required', 'exists:roles,id'],
        ]);

        // Mise à jour explicite (pas besoin d'ajouter aux $fillable)
        $user->nom           = $validated['nom'];
        $user->prenom        = $validated['prenom'];
        $user->email         = $validated['email'];
        $user->id_role       = $validated['id_role'];

        $user->save();

        return redirect()
            ->route('admin.utilisateurs.index')
            ->with('status', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.utilisateurs.index')
            ->with('status', 'Utilisateur supprimé.');
    }
    
}
