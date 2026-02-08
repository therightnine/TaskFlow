<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Abonnement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUsersController extends Controller
{
    public function index()
    {
        // On charge les relations utiles (role et l'abonnement actuel)
        $users = User::with(['role', 'currentAbonnement.abonnement'])->get();

        $roles = Role::orderBy('role')->get();
        //$abonnements = Abonnement::orderBy('abonnement')->get();

        return view('utilisateurs.gest_utilisateurs', compact('users'));//, 'roles', 'abonnements'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nom'           => ['required', 'string', 'max:100'],
            'prenom'        => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            //'id_role'       => ['required', 'exists:roles,id'],
            //'id_abonnement' => ['nullable', 'exists:abonnements,id'],
        ]);

        // Mise à jour des informations de base
        $user->nom           = $validated['nom'];
        $user->prenom        = $validated['prenom'];
        $user->email         = $validated['email'];
        //$user->id_role       = $validated['id_role'];

        $user->save();

        // Gestion de l'abonnement
        /*if (!empty($validated['id_abonnement'])) {
            // Créer ou mettre à jour l'abonnement de l'utilisateur
            $userAbonnement = $user->abonnements()->latest('date_debut')->first();
            
            // Si pas d'abonnement actif ou si l'abonnement change
            if (!$userAbonnement || $userAbonnement->id_abonnement != $validated['id_abonnement']) {
                // Terminer l'ancien abonnement s'il existe
                if ($userAbonnement && !$userAbonnement->date_fin) {
                    $userAbonnement->date_fin = now();
                    $userAbonnement->save();
                }
                
                // Créer le nouvel abonnement
                $user->abonnements()->create([
                    'id_abonnement' => $validated['id_abonnement'],
                    'date_debut'    => now(),
                    'date_fin'      => null, // Abonnement illimité par défaut
                ]);
            }
        } else {
            // Si pas d'abonnement sélectionné, terminer l'abonnement actuel
            $currentAbonnement = $user->abonnements()->whereNull('date_fin')->first();
            if ($currentAbonnement) {
                $currentAbonnement->date_fin = now();
                $currentAbonnement->save();
            }
        }*/

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