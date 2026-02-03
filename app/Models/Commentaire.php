<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    protected $table = 'commentaires';
    public $timestamps = true;

    protected $fillable = [
        'texte',
        'date_comment',
        'id_user',
        'id_tache',
    ];

    public function tache()
    {
        return $this->belongsTo(Tache::class, 'id_tache');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
