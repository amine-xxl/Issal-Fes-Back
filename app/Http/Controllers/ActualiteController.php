<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActualiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Actualite::orderBy('created_at', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre'   => 'required',
            'contenu' => 'required',
            'image'   => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['titre', 'contenu']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('actualites', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $actualite = Actualite::create($data);
        return response()->json($actualite, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Actualite $actualite)
    {
        return response()->json($actualite);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Actualite $actualite)
    {
        $request->validate([
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['titre', 'contenu']);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($actualite->image) {
                $oldPath = str_replace('/storage/', '', $actualite->image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('actualites', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $actualite->update($data);
        return response()->json($actualite);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Actualite $actualite)
    {
        // Delete image file if exists
        if ($actualite->image) {
            $oldPath = str_replace('/storage/', '', $actualite->image);
            Storage::disk('public')->delete($oldPath);
        }

        $actualite->delete();
        return response()->json(['message' => 'Actualité supprimée']);
    }
}
