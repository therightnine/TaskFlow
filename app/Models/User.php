<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id';

    public $timestamps = false; // IMPORTANT (your table has no created_at)

    protected $hidden = ['pw'];

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    // Tell Laravel to use `pw` as password
    public function getAuthPassword()
    {
        return $this->pw;
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors (THIS IS THE KEY 🔑)
    |--------------------------------------------------------------------------
    */

    // Make Laravel think "name" exists
    public function getNameAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }
}
