<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Contrôleur UserController
 * 
 * Permet aux utilisateurs de gérer leurs informations personnelles (profil).
 */
class UserController extends Controller
{
    /**
     * Met à jour le nom de l'utilisateur connecté.
     */
    public function update(Request $request)
    {
        // Validation : s'assure que le nouveau nom respecte les contraintes
        $request->validate([
            'name' => 'required|string|max:100',
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'name.max'      => 'Le nom ne doit pas dépasser 100 caractères.',
        ]);

        // Récupération de l'instance de l'utilisateur authentifié via le middleware auth:sanctum
        $user = $request->user();
        $user->update(['name' => $request->name]);

        // On retourne l'objet user mis à jour pour synchroniser le Front-end
        return response()->json(['user' => $user]);
    }

    /**
     * Change le mot de passe de l'utilisateur connecté.
     */
    public function updatePassword(Request $request)
    {
        // Validation : requiert l'ancien mot de passe pour des raisons de sécurité
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'password.required'         => 'Le nouveau mot de passe est obligatoire.',
            'password.min'              => 'Le nouveau mot de passe doit faire au moins 6 caractères.',
            'password.confirmed'        => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $user = $request->user();

        // Vérification de la validité du mot de passe actuel avant modification
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Le mot de passe actuel est incorrect.',
            ], 422);
        }

        // Mise à jour sécurisée avec hashage du nouveau mot de passe
        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Mot de passe modifié avec succès.']);
    }
}
