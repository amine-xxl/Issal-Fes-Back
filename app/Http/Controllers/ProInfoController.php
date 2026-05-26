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
    public function getDriverInfo()
    {
        $user = Auth::user();

        if ($user->role !== 'chauffeur') {
            return response()->json(['message' => 'Accès réservé aux chauffeurs'], 403);
        }

        $proInfo = ProInfo::with(['ligne.itineraires' => function ($query) {
            $query->orderBy('ordre', 'asc');
        }])->where('user_id', $user->id)->first();

        if (!$proInfo) {
            return response()->json(['message' => 'Aucune information professionnelle trouvée'], 404);
        }

        $alertes = Alerte::where('ligne_id', $proInfo->ligne_id)
            ->where('statut', 'active')
            ->latest()
            ->get();

        return response()->json([
            'pro_info' => $proInfo,
            'alertes'  => $alertes,
        ]);
    }

    public function index()
    {
        $chauffeurs = User::where('role', 'chauffeur')
            ->with('proInfo.ligne')
            ->get();

        return response()->json($chauffeurs);
    }

    // FIX: updateOrCreate utilisait user_id dans les deux params — corrigé
    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'ligne_id'   => 'required|exists:lignes,id',
            'numero_bus' => 'required|string|max:10',
            'modele'     => 'required|string|max:50',
            'capacite'   => 'required|integer|min:1',
            'trajet'     => 'required|string',
            'tarif'      => 'required|numeric|min:0',
        ]);

        // Vérifie que l'user est bien un chauffeur
        $user = User::find($request->user_id);
        if (!$user || $user->role !== 'chauffeur') {
            return response()->json(['message' => 'L\'utilisateur doit être un chauffeur'], 422);
        }

        $proInfo = ProInfo::updateOrCreate(
            ['user_id' => $request->user_id],      // critère de recherche
            $request->only([                         // données à insérer/mettre à jour
                'ligne_id',
                'numero_bus',
                'modele',
                'capacite',
                'trajet',
                'tarif',
            ])
        );

        return response()->json([
            'message'  => 'Affectation enregistrée avec succès.',
            'pro_info' => $proInfo->load('ligne'),
        ]);
    }

    public function show(ProInfo $proInfo)
    {
        return response()->json($proInfo->load('ligne'));
    }

    public function update(Request $request, ProInfo $proInfo)
    {
        $request->validate([
            'ligne_id'   => 'sometimes|exists:lignes,id',
            'numero_bus' => 'sometimes|string|max:10',
            'modele'     => 'sometimes|string|max:50',
            'capacite'   => 'sometimes|integer|min:1',
            'trajet'     => 'sometimes|string',
            'tarif'      => 'sometimes|numeric|min:0',
        ]);

        $proInfo->update($request->only([
            'ligne_id',
            'numero_bus',
            'modele',
            'capacite',
            'trajet',
            'tarif',
        ]));

        return response()->json($proInfo->load('ligne'));
    }

    public function destroy(ProInfo $proInfo)
    {
        $proInfo->delete();
        return response()->json(['message' => 'Informations supprimées']);
    }
}
