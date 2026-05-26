<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Modèle User
 * 
 * Ce modèle gère les utilisateurs du système (Passagers, Chauffeurs, Administrateurs).
 * Il utilise Laravel Sanctum pour l'authentification via API.
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'name',
        'email', 
        'password',
        'role',   // Rôle de l'utilisateur : passager, chauffeur, admin
        'active', // Statut du compte
    ];

    /**
     * Les attributs qui doivent être cachés pour les tableaux (comme l'API).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast des attributs vers des types natifs.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed', // Hashage automatique du mot de passe
        ];
    }

    /**
     * Relation : Un utilisateur peut acheter plusieurs tickets.
     * Relation de type Un-à-Plusieurs (One-to-Many).
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Relation : Un utilisateur (s'il est chauffeur) possède des informations professionnelles.
     * Relation de type Un-à-Un (One-to-One).
     */
    public function proInfo()
    {
        return $this->hasOne(ProInfo::class);
    }
}