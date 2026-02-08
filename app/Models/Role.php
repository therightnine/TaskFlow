<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    
    protected $role = 'roles'; // correspond à ta table existante

    // champs modifiables (si tu fais du CRUD)
    protected $fillable = [
        'role',   // nom du role (Admin, User…)
        'description',  // texte libre
    ];

    
// Désactive les colonnes created_at / updated_at
    public $timestamps = false;

    
    protected $table = 'roles';
      public function users() {
        return $this->hasMany(User::class, 'id_role');
    }
}
