<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tache extends Model
{
    protected $table = 'taches';
    public $timestamps = false;

    protected $fillable = [
        'nom_tache',
        'description',
        'priorite',
        'deadline',
        'id_projet',
        'id_etat',
    ];
    protected $dates = [
    'created_at',
    'updated_at',
    'deadline', // si tu veux utiliser Carbon pour deadline
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
        return $this->hasMany(Commentaire::class, 'id_tache')->orderBy('created_at', 'desc');
    }

    public function contributeurs()
    {
        return $this->belongsToMany(
            User::class,
            'tache_contributeur',
            'id_tache',
            'id_user'
        );
    }

    public function superviseurs()
    {
        return $this->belongsToMany(
            User::class,
            'tache_superviseur',
            'id_tache',
            'id_user'
        );
    }

    }

