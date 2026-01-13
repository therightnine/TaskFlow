<?php

namespace App\Http\Controllers;
use App\Models\Role;
use Illuminate\Http\Request;    
class RoleController extends Controller
{
    
    public function gest_roles()
    {
        $roles = Role::all();
        return view('roles.gest_roles', compact('roles'));
    }
     public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
        ]);

        Role::create($validated);
        return redirect()->route('admin.roles.gest_roles')->with('success', 'Rôle créé.');
    }

    
// Edit: afficher le formulaire

// Edit: affiche le formulaire (déjà correct)
public function edit(Role $role) {
    return view('roles.edit', compact('role'));
}

// Update: traiter la mise à jour (corrigé)
public function update(Request $request, Role $role) {
    $validated = $request->validate([
        'role'  => ['required', 'string', 'max:150'],
        'description' => ['nullable', 'string', 'max:500'],
    ]);

    $role->update($validated);

    return redirect()->route('admin.roles.gest_roles')
                     ->with('status', 'Rôle mis à jour avec succès.');
}

    public function destroy (Request $request, Role $role)
{
    $id = $role->id;
    $role->delete();

    // Si l’appel AJAX envoie Accept: application/json, on renvoie un JSON ou 204
    if ($request->expectsJson()) {
        return response()->json([
            'message' => 'Rôle supprimé.',
            'id' => $id,
        ], 200);
        // ou : return response()->noContent(); // 204 No Content
    }

    // fallback (cas non-AJAX)
    return redirect()->route('admin.roles.gest_roles')->with('success', 'Rôle supprimé.');



    }



}
