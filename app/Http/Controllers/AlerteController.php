<?php

namespace App\Http\Controllers;

use App\Models\Alerte;
use Illuminate\Http\Request;

/**
 * Contrôleur AlerteController
 * 
 * Ce contrôleur gère les alertes en temps réel concernant les lignes de bus.
 * Il permet d'informer les utilisateurs de tout incident sur le réseau.
 */
class AlerteController extends Controller
{
    /**
     * Liste toutes les alertes, en incluant les informations de la ligne concernée.
     * Utilise le "Eager Loading" (with('ligne')) pour optimiser les requêtes SQL.
     */
    public function index()
    {
        return response()->json(Alerte::with('ligne')->get());
    }

    /**
     * Enregistre une nouvelle alerte.
     */
    public function store(Request $request)
    {
        // Validation stricte des données de l'alerte
        $request->validate([
            'ligne_id' => 'required|exists:lignes,id', // Doit correspondre à une ligne existante
            'type'     => 'required|in:retard,perturbation,info',
            'message'  => 'required',
            'statut'   => 'required|in:active,resolue',
        ]);

        $alerte = Alerte::create($request->all());
        // Retourne l'alerte créée avec les infos de la ligne
        return response()->json($alerte->load('ligne'), 201);
    }

    /**
     * Affiche les détails d'une alerte spécifique.
     */
    public function show(Alerte $alerte)
    {
        return response()->json($alerte->load('ligne'));
    }

    /**
     * Met à jour les informations d'une alerte (ex: changer le statut de 'active' à 'resolue').
     */
    public function update(Request $request, Alerte $alerte)
    {
        $alerte->update($request->all());
        return response()->json($alerte->load('ligne'));
    }

    /**
     * Supprime une alerte du système.
     */
    public function destroy(Alerte $alerte)
    {
        $alerte->delete();
        return response()->json(['message' => 'Alerte supprimée']);
    }
}
