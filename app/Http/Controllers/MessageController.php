<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Contrôleur MessageController
 * Gère l'envoi des messages depuis le formulaire de contact du Front-end.
 */
class MessageController extends Controller
{
    /**
     * Enregistre un message de contact dans la base de données.
     */
    public function store(Request $request)
    {
        // Validation des champs du formulaire de contact
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ], [
            'name.required'    => 'Le nom est obligatoire.',
            'email.required'   => "L'email est obligatoire.",
            'email.email'      => "L'email n'est pas valide.",
            'subject.required' => 'Le sujet est obligatoire.',
            'message.required' => 'Le message est obligatoire.',
        ]);

        // Création de l'entrée dans la table 'messages'
        Message::create($request->all());

        return response()->json(['message' => 'Message envoyé avec succès !'], 201);
    }

    /**
     * Liste tous les messages (Admin uniquement).
     */
    public function index()
    {
        $messages = Message::latest()->get();
        return response()->json($messages); // Affiche les messages du plus récent au plus ancien
    }

    /**
     * Supprime un message (Admin uniquement).
     */
    public function destroy(Message $message)
    {
        $message->delete();
        return response()->json(['message' => 'Message supprimé avec succès.']);
    }
}
