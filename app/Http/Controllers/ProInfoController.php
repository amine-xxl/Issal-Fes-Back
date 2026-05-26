<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProInfo;
use App\Models\Alerte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur ProInfoController
 * 
 * Gère les informations professionnelles des chauffeurs.
 */
class ProInfoController extends Controller
{
    /**
     * Récupère les informations du chauffeur connecté.
     * Inclut le bus assigné, la ligne, les arrêts de l'itinéraire et les alertes actives.
     */
    public function getDriverInfo()
    {
        $user = Auth::user();

        // On vérifie que c'est bien un chauffeur
        if ($user->role !== 'chauffeur') {
            return response()->json(['message' => 'Accès réservé aux chauffeurs'], 403);
        }

        // On récupère ses infos professionnelles avec la ligne et ses itinéraires (arrêts)
        $proInfo = ProInfo::with(['ligne.itineraires' => function($query) {
            $query->orderBy('ordre', 'asc'); 
        }])->where('user_id', $user->id)->first();

        if (!$proInfo) {
            return response()->json(['message' => 'Aucune information professionnelle trouvée'], 404);
        }

        // On récupère les alertes actives pour la ligne assignée
        $alertes = Alerte::where('ligne_id', $proInfo->ligne_id)
            ->where('statut', 'active')
            ->latest()
            ->get();

        return response()->json([
            'pro_info' => $proInfo,
            'alertes' => $alertes
        ]);
    }

    /**
     * Liste tous les chauffeurs et leurs affectations (Admin uniquement).
     */
    public function index()
    {
        $chauffeurs = User::where('role', 'chauffeur')
            ->with('proInfo.ligne')
            ->get();
            
        return response()->json($chauffeurs);
    }

    /**
     * Enregistre ou met à jour l'affectation d'un chauffeur.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'ligne_id'   => 'required|exists:lignes,id',
            'numero_bus' => 'required|string|max:10',
            'modele'     => 'required|string|max:50',
            'capacite'   => 'required|integer',
            'trajet'     => 'required|string',
            'tarif'      => 'required|numeric',
        ]);

        $proInfo = ProInfo::updateOrCreate(
            ['user_id' => $request->user_id],
            $request->all()
        );

        return response()->json([
            'message' => 'Affectation enregistrée avec succès.',
            'pro_info' => $proInfo->load('ligne')
        ]);
    }

    /**
     * Affiche les infos pro d'un chauffeur spécifique.
     */
    public function show(ProInfo $proInfo)
    {
        return response()->json($proInfo);
    }

    /**
     * Met à jour les infos professionnelles.
     */
    public function update(Request $request, ProInfo $proInfo)
    {
        $proInfo->update($request->all());
        return response()->json($proInfo);
    }

    /**
     * Supprime les infos pro.
     */
    public function destroy(ProInfo $proInfo)
    {
        $proInfo->delete();
        return response()->json(['message' => 'Informations supprimées']);
    }
}
