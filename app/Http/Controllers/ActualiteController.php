<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Contrôleur ActualiteController
 * 
 * Ce contrôleur gère les opérations CRUD (Créer, Lire, Mettre à jour, Supprimer)
 * pour les actualités affichées sur le site.
 */
class ActualiteController extends Controller
{
    /**
     * Récupère toutes les actualités triées par date de création (la plus récente d'abord).
     */
    public function index()
    {
        return response()->json(Actualite::orderBy('created_at', 'desc')->get());
    }

    /**
     * Enregistre une nouvelle actualité avec possibilité de télécharger une image.
     */
    public function store(Request $request)
    {
        // Validation des données entrantes
        $request->validate([
            'titre'   => 'required',
            'contenu' => 'required',
            'image'   => 'nullable|image|max:2048', // Image optionnelle, max 2Mo
        ]);

        $data = $request->only(['titre', 'contenu']);

        // Gestion du téléchargement de l'image si elle est présente
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('actualites', 'public');
            $data['image'] = '/storage/' . $path; // Chemin accessible via le lien symbolique storage
        }

        $actualite = Actualite::create($data);
        return response()->json($actualite, 201);
    }

    /**
     * Affiche les détails d'une actualité spécifique.
     */
    public function show(Actualite $actualite)
    {
        return response()->json($actualite);
    }

    /**
     * Met à jour une actualité existante et gère le remplacement de l'image.
     */
    public function update(Request $request, Actualite $actualite)
    {
        $request->validate([
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['titre', 'contenu']);

        if ($request->hasFile('image')) {
            // Suppression de l'ancienne image du serveur pour économiser de l'espace
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
     * Supprime une actualité et son fichier image associé.
     */
    public function destroy(Actualite $actualite)
    {
        if ($actualite->image) {
            $oldPath = str_replace('/storage/', '', $actualite->image);
            Storage::disk('public')->delete($oldPath);
        }

        $actualite->delete();
        return response()->json(['message' => 'Actualité supprimée']);
    }
}
