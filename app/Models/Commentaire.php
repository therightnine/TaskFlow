<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commentaire extends Model
{
    use HasFactory;

    protected $table = 'commentaires';

    public $timestamps = true;

    protected $fillable = [
        'texte',
        'id_user',
        'id_tache',
    ];

    /**
     * Auteur du commentaire
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Tâche associée au commentaire
     */
    public function task()
    {
        return $this->belongsTo(Tache::class, 'id_tache');
    }
}
