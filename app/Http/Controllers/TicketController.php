<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

/**
 * Contrôleur TicketController
 * 
 * Gère le cycle de vie des tickets (achat, validation, historique).
 */
class TicketController extends Controller
{
    /**
     * Liste les tickets de l'utilisateur connecté.
     */
    public function index(Request $request)
    {
        return response()->json($request->user()->tickets()->with('ligne')->get());
    }

    /**
     * Enregistre l'achat d'un nouveau ticket.
     */
    public function store(Request $request)
    {
        // Validation et création du ticket pour l'utilisateur authentifié
    }

    /**
     * Affiche un ticket spécifique.
     */
    public function show(Ticket $ticket)
    {
        return response()->json($ticket->load('ligne'));
    }

    /**
     * Met à jour le statut d'un ticket (ex: marqué comme 'utilisé').
     */
    public function update(Request $request, Ticket $ticket)
    {
        $ticket->update($request->all());
        return response()->json($ticket);
    }

    /**
     * Supprime ou annule un ticket.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return response()->json(['message' => 'Ticket supprimé']);
    }
}
