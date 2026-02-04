<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    protected $table = 'projets';

    /* =========================
       Propriétaire (Chef)
       ========================= */
    public function owner()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /* =========================
       Superviseurs (many-to-many)
       ========================= */
    public function superviseurs()
    {
        return $this->belongsToMany(
            User::class,
            'projet_superviseur', // 🔴 table pivot
            'projet_id',           // FK projet
            'user_id'              // FK user
        );
    }

    /* =========================
       Contributeurs (many-to-many)
       ========================= */
    public function contributeurs()
    {
        return $this->belongsToMany(
            User::class,
            'projet_contributeur', // 🔴 table pivot
            'projet_id',           // FK projet
            'user_id'
        );
    }

    /* =========================
       Tâches
       ========================= */
    public function taches()
    {
        return $this->hasMany(Tache::class, 'id_projet');
    }
}
