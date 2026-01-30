<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Projet extends Model
{
    protected $table = 'projets';
    protected $fillable = [
        'nom_projet',
        'description',
        'date_debut',
        'deadline',
        'id_user',
        'id_etat',
        'is_favorite'
    ];

    // Chef de projet
    public function owner()
    {
        return $this->belongsTo(User::class, 'id_user');
    }



    // Contributors
    public function contributors()
    {
        return $this->belongsToMany(
            User::class,
            'projet_contributeur',
            'projet_id',
            'user_id'
        );
    }

    // Supervisors
    public function superviseurs()
    {
        return $this->belongsToMany(
            User::class,
            'projet_superviseur',
            'projet_id',
            'user_id'
        );
    }

    public function etat()
    {
        return $this->belongsTo(Etat::class, 'id_etat');
    }

     

    public function taches()
    {
        return $this->hasMany(Tache::class, 'id_projet');
    }

    // Calcul du pourcentage d'avancement
    public function getProgressAttribute(): int
    {
        $total = $this->taches->count();

        if ($total === 0) {
            return 0;
        }

        $done = $this->taches->where('id_etat', 3)->count();

        return (int) round(($done / $total) * 100);
    }

   
    // Calcul du temps restant
    public function getTimeRemainingAttribute(): array
    {
        $now = Carbon::now();
        $deadline = Carbon::parse($this->deadline);

        if ($deadline->isPast()) {
            return [
                'label' => 'En retard',
                'color' => 'bg-red-100 text-red-700'
            ];
        }

        $days = $now->diffInDays($deadline);

        if ($days >= 30) {
            return [
                'label' => '≈ ' . floor($days / 30) . ' mois',
                'color' => 'bg-green-100 text-green-700'
            ];
        }

        if ($days >= 14) {
            return [
                'label' => '≈ ' . floor($days / 7) . ' semaines',
                'color' => 'bg-yellow-100 text-yellow-700'
            ];
        }

        return [
            'label' => $days . ' jours',
            'color' => 'bg-purple-100 text-purple-700'
        ];
    }



// Convenience to get all team members
public function teamMembers() {
    return $this->owner
        ? collect([$this->owner])
            ->merge($this->superviseurs)
            ->merge($this->contributors)
        : $this->superviseurs->merge($this->contributors);
}

}

