<?php

namespace App\Http\Controllers;

use App\Models\Alerte;
use Illuminate\Http\Request;

class AlerteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Alerte::with('ligne')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ligne_id' => 'required|exists:lignes,id',
            'type'     => 'required|in:retard,perturbation,info',
            'message'  => 'required',
            'statut'   => 'required|in:active,resolue',
        ]);

        $alerte = Alerte::create($request->all());
        return response()->json($alerte->load('ligne'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Alerte $alerte)
    {
        return response()->json($alerte->load('ligne'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alerte $alerte)
    {
        $alerte->update($request->all());
        return response()->json($alerte->load('ligne'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alerte $alerte)
    {
        $alerte->delete();
        return response()->json(['message' => 'Alerte supprimée']);
    }
}
