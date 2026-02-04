<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projet;
use App\Models\User;
use App\Models\ProjetContributeur;
use App\Models\ProjetSuperviseur;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EquipeController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Fetch all projects related to the user
        $projets = Projet::with(['owner', 'superviseurs', 'contributors'])
            ->where('id_user', $userId)
            ->orWhereHas('contributors', fn($q) => $q->where('user_id', $userId))
            ->orWhereHas('superviseurs', fn($q) => $q->where('user_id', $userId))
            ->get();

        // Collect all team members across all projects (including owners)
        $allMembers = $projets->flatMap(function ($projet) {
            $members = collect();

            if ($projet->owner) {
                $members->push($projet->owner);
            }

            if ($projet->contributors) {
                $members = $members->merge($projet->contributors);
            }

            if ($projet->superviseurs) {
                $members = $members->merge($projet->superviseurs);
            }

            return $members;
        })->unique('id')->values();

        return view('equipe.index', compact('projets', 'allMembers'));
    }




    public function show(User $user)
    {
        // Determine projects based on role
        switch ($user->role->id) {

            case 3: // chef de projet
                $projets = Projet::where('id_user', $user->id)->get();
                break;

            case 2: // superviseur
                $projets = $user->projetsSupervised;
                break;

            default: // membre / contributeur
                $projets = $user->projetsContributed;
                break;
        }

        // --- Membre depuis (Option B, already discussed) ---
        $dates = collect();

        foreach ($user->projetsContributed as $projet) {
            $dates->push($projet->pivot?->created_at);
        }

        foreach ($user->projetsSupervised as $projet) {
            $dates->push($projet->pivot?->created_at);
        }

        $membreDepuis = $dates->filter()->min();

        return view(
            'equipe.partials.profile',
            compact('user', 'projets', 'membreDepuis')
        );
    }
}
