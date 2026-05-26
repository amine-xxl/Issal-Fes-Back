<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Contrôleur AuthController
 *
 * Gère l'authentification via Laravel Sanctum.
 */
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            // FIX: admin ajouté pour permettre la création via seeder/API
            'role'     => 'in:client,chauffeur,admin',
        ], [
            'name.required'    => 'Veuillez Entrer Votre Nom !',
            'name.string'      => 'Votre Nom Ne Doit Pas Contenir Des Nombres !',
            'name.max'         => 'Votre Nom Doit Être Moins De 100 Caractères !',
            'email.required'   => 'Veuillez Entrer Votre Email !',
            'email.email'      => 'Veuillez Entrer Un Email Valide !',
            'email.unique'     => 'Cet Email Existe Déjà, Veuillez En Entrer Un Autre !',
            'password.required' => 'Veuillez Entrer Votre Mot De Passe !',
            'password.min'     => "Veuillez Entrer Un Mot De Passe D'Au Moins 6 Caractères !",
            'password.confirmed' => 'Veuillez Confirmer Votre Mot De Passe !',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role ?? 'client',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Veuillez Entrer Votre Email !',
            'email.email'       => 'Veuillez Entrer Un Email Valide !',
            'password.required' => 'Veuillez Entrer Votre Mot De Passe !',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }
}
