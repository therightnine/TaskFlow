<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Etat;
use App\Models\Projet;
use App\Models\User;

class Tache extends Model
{
    protected $table = 'taches';

    protected $fillable = [
        'nom_tache',
        'description',
        'priorite',
        'deadline',
        'id_projet',
        'id_etat',
    ];

    public function projet()
    {
        return $this->belongsTo(Projet::class, 'id_projet');
    }

    public function etat()
    {
        return $this->belongsTo(Etat::class, 'id_etat');
    }
     public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'id_tache');
    }

    /**
     * Contributeurs de la tâche
     */
    public function contributeurs()
    {
        return $this->belongsToMany(
            User::class,
            'tache_contributeur',
            'id_tache',
            'id_user'
        );
    }

    public function isOverdue()
    {
        return $this->deadline < now()
            && $this->etat
            && $this->etat->etat !== 'terminé';
    }
}
