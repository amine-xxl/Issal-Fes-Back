<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Itineraire
 * 
 * Ce modèle représente un arrêt spécifique (station) faisant partie du trajet d'une ligne.
 * Il permet de définir le parcours exact d'un bus.
 */
class Itineraire extends Model
{
    /**
     * Attributs remplissables.
     * 
     * - ligne_id : Identifiant de la ligne parente.
     * - direction : Sens du trajet ('aller' ou 'retour').
     * - nom_arret : Le nom de la station de bus.
     * - ordre : La position de cet arrêt dans la suite logique du trajet.
     */
    protected $fillable = [
        'ligne_id',
        'direction',
        'nom_arret',
        'ordre',
    ];

    /**
     * Relation : Chaque arrêt appartient à une et une seule ligne.
     * Relation inverse du HasMany défini dans le modèle Ligne.
     */
    public function ligne()
    {
        return $this->belongsTo(Ligne::class);
    }
}
