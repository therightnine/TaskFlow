<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Load projects according to the current user's role.
        $projects = match ((int) $user->id_role) {
            // Chef / createur: owned projects
            3 => $user->projetsOwned()->get(),

            // Superviseur: supervised projects
            2 => $user->projetsSupervised()->get(),

            // Contributeur: contributed projects
            4 => $user->projetsContributed()->get(),

            // Fallback (admin/other): all linked projects
            default => $user->allProjects(),
        };

        $projects = collect($projects)
            ->unique('id')
            ->sortByDesc('date_debut')
            ->values();

        return view('chef.profile', compact('user', 'projects'));
    }

    public function updateBio(Request $request)
    {
        $request->validate([
            'bio' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $user->bio = $request->bio;
        $user->save();

        // Return JSON
        return response()->json([
            'success' => true,
            'bio' => $user->bio,
        ]);
    }
}
