<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Role;
use App\Models\Projet;
use App\Models\Tache;
use App\Models\UserAbonnement;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

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
        'photo',
        'id_role'
    ];

    /* =======================
     * AUTH
     * ======================= */
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getNameAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /* =======================
     * RELATIONS
     * ======================= */

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    

    // Projects I own
    public function projetsOwned()
    {
        return $this->hasMany(Projet::class, 'id_user');
    }
    
    public function abonnements()
    {
        return $this->hasMany(UserAbonnement::class, 'id_inscri');
    }

      public function currentAbonnement()
    {
    return $this->hasOne(UserAbonnement::class, 'id_inscri')
        ->where(function($query) {
            $query->where('date_fin', '>=', now())
                  ->orWhereNull('date_fin');
        })
        ->latest('date_debut');
    }


    public function ownedProjects()
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
        )->withTimestamps();
    }

    // Projects I supervise
    public function projetsSupervised()
    {
        return $this->belongsToMany(
            Projet::class,
            'projet_superviseur',
            'user_id',
            'projet_id'
        )->withTimestamps();
    }

    public function allProjects()
    {
        $owned = Projet::where('id_user', $this->id)->get();
        $contrib = $this->projetsContributed; // eager loaded or relationship
        $superv = $this->projetsSupervised;  // eager loaded or relationship

        return $owned->merge($contrib)->merge($superv)->unique('id')->values();
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

    

    public function projetsContributes()
    {
        return $this->belongsToMany(
            Projet::class,
            'projet_contributeur',
            'user_id',
            'projet_id'
        )->withTimestamps();
    }

    public function isSuperviseur()
    {
        return $this->role->name === 'superviseur';
    }

    public function isChef()
    {
        return $this->role->name === 'chef de projet';
    }

    public function isContributeur()
    {
        return $this->role->name === 'contributeur';
    }

    




}
