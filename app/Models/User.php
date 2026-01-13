<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
use App\Models\Projet; 
class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $hidden = ['password'];

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'phone',
        'password',
        'date_naissance',
        'profession',
        'photo'
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getNameAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    // Projects I own
    public function projetsOwned()
    {
        return $this->hasMany(Projet::class, 'id_user');
    }

    // Projects I contribute to
    public function projetsContributed()
    {
        return $this->belongsToMany(
            Projet::class,
            'projet_contributeur',
            'user_id',
            'projet_id'
        );
    }

    // Projects I supervise
    public function projetsSupervised()
    {
        return $this->belongsToMany(
            Projet::class,
            'projet_superviseur',
            'user_id',
            'projet_id'
        );
    }

    public function contributedTasks()
    {
        return $this->belongsToMany(
            Tache::class,
            'tache_contributeur',
            'user_id',
            'tache_id'
        );
    }


}
