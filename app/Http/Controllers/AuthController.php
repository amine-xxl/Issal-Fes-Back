<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Contrôleur AuthController
 * 
 * Ce contrôleur gère l'authentification des utilisateurs.
 * Il utilise Laravel Sanctum pour générer des jetons (tokens) API sécurisés.
 */
class AuthController extends Controller
{
    /**
     * Gère l'inscription des nouveaux utilisateurs.
     */
    public function register(Request $request)
    {
        // Validation des données reçues du formulaire avec messages personnalisés
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed', // Vérifie 'password' et 'password_confirmation'
            'role'     => 'in:client,chauffeur',
        ],[
            'name.required'=>'Veuillez Entrer Votre Nom !',
            'name.string'=>'Votre Ne Doit Pas Contenir Des Nombres !',
            'name.max'=>'Votre Nom doit Etre Moins De 100 Charactere !',
            'email.required'=>'Veuillez Entrer Votre Email !',
            'email.email'=>'Veuillez Entrer Un Email Valide !',
            'email.unique'=>'Cette Email Existe Déja Veuillez Entrer Un Autre Email !',
            'password.required'=>'Veuillez Entrer Votre Mot De Passe !',
            'password.min'=>'Veuillez Entrer Un Mot De Passe D\'Au Moins 6 Charactere !',
            'password.confirmed'=>'Veuillez Confirmer Votre Mot De Passe !',
        ]);

        // Création de l'utilisateur avec hashage sécurisé du mot de passe
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Sécurité : ne jamais stocker en clair
            'role'     => $request->role,
        ]);

        // Génération d'un token Sanctum pour la session immédiate
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Gère la connexion des utilisateurs existants.
     */
    public function login(Request $request)
    {
        // Validation des identifiants
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ],[
            'email.required'=>'Veuillez Entrer Votre Email !',
            'email.email'=>'Veuillez Entrer Un Email Valide !',
            'password.required'=>'Veuillez Entrer Votre Mot De Passe !',
        ]);

        // Recherche de l'utilisateur par son adresse email
        $user = User::where('email', $request->email)->first();

        // Vérification de l'existence et du mot de passe
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        // Création d'un nouveau token API pour cet utilisateur
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }
}