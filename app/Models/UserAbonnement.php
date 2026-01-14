<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAbonnement extends Model
{
    use HasFactory;

    protected $table = 'user_abonnement'; // Ajustez selon votre nom de table
    
    protected $fillable = [
        'id_inscri',       // ID de l'utilisateur
        'id_abonnement',   // ID de l'abonnement
        'date_debut',
        'date_fin',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin'   => 'datetime',
    ];

    public $timestamps = true;

    // Relation vers l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class, 'id_inscri');
    }

    // Relation vers l'abonnement
    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class, 'id_abonnement');
    }

    // Vérifier si l'abonnement est actif
    public function isActive()
    {
        if (!$this->date_fin) {
            return true; // Abonnement illimité
        }
        
        return $this->date_fin >= now();
    }

    // Vérifier si l'abonnement expire bientôt (dans les 7 jours)
    public function isExpiringSoon()
    {
        if (!$this->date_fin) {
            return false;
        }
        
        $daysRemaining = now()->diffInDays($this->date_fin, false);
        return $daysRemaining >= 0 && $daysRemaining <= 7;
    }

    // Obtenir le nombre de jours restants
    public function daysRemaining()
    {
        if (!$this->date_fin) {
            return null; // Illimité
        }
        
        return max(0, now()->diffInDays($this->date_fin, false));
    }

    // Scope pour les abonnements actifs
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('date_fin')
              ->orWhere('date_fin', '>=', now());
        });
    }
}