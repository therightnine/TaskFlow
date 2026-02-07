<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Tache;

class Projet extends Model
{
    protected $table = 'projets';

    // Chef de projet
    public function chef()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // ✅ Contributeurs (TABLE QUE TU AS DONNÉE)
    public function contributeurs()
    {
        return $this->belongsToMany(
            User::class,
            'projet_contributeur',
            'projet_id',
            'user_id'
        )->withTimestamps();
    }

    // Superviseurs
    public function superviseurs()
    {
        return $this->belongsToMany(
            User::class,
            'projet_superviseur',
            'projet_id',
            'user_id'
        )->withTimestamps();
    }

    // Tâches
    public function taches()
    {
        return $this->hasMany(Tache::class, 'id_projet');
    }
}
