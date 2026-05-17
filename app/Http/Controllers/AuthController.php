<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Inscription
    public function register(Request $request)
    {
        // Valider les données reçues a partir de formulaire
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
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

        // Créer le user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // anti-hashage (raison de securite)
            'role'     => $request->role,
        ]);

        // Créer le token d'authentificaton
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    // Connexion
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ],[
            'email.required'=>'Veuillez Entrer Votre Email !',
            'email.email'=>'Veuillez Entrer Un Email Valide !',
            'password.required'=>'Veuillez Entrer Votre Mot De Passe !',
        ]);

        // Chercher le user par email et retourner le premier
        $user = User::where('email', $request->email)->first();

        // Vérifier le mot de passe
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        // Créer le token d'authentification
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }
}