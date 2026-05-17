<?php

namespace App\Http\Controllers;

use App\Models\Ligne;
use Illuminate\Http\Request;

class LigneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Ligne::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero'      => 'required|unique:lignes',
            'depart'      => 'required',
            'arrivee'     => 'required',
            'description' => 'required',
        ]);

        $ligne = Ligne::create($request->all());
        return response()->json($ligne, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ligne $ligne)
    {
        return response()->json($ligne);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ligne $ligne)
    {
        $ligne->update($request->all());
        return response()->json($ligne);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ligne $ligne)
    {
        $ligne->delete();
        return response()->json(['message' => 'Ligne supprimée']);
    }
}
