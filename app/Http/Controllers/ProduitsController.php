<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produits;

class ProduitsController extends Controller
{

    public function index()
    {
        $produits = Produits::all();

        return response()->json($produits);
    }

    public function getType()
    {
        $accessoires = Produits::distinct()->pluck('type');

        return response()->json($accessoires);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'designation' => 'required',
            'description' => 'required',
            'prix' => 'required|numeric',
            'stock' => 'required|numeric',
            'type' => 'required',
            'couleur' => 'nullable|string',
            'reduction' => 'nullable|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);

            // Création du produit avec les données et le nom d'image
            Produits::create([
                'designation' => $validatedData['designation'],
                'description' => $validatedData['description'],
                'prix' => $validatedData['prix'],
                'stock' => $validatedData['stock'],
                'type' => $validatedData['type'],
                'couleur' => $validatedData['couleur'],
                'reduction' => $validatedData['reduction'],
                'image' => $imageName,
            ]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

    public function storea(Request $request)
    {
        return response()->json(['Uploaded' => true]);
    }

    public function show($id)
    {
        $produit = Produits::find($id);

        if (!$produit) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }

        return response()->json($produit);
    }

    public function remove($id)
    {
        $produit = Produits::find($id);

        if (!$produit) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }

        $produit->delete();

        return response()->json(['success' => true]);
    }

    public function edit(Request $request, $id)
    {
        $produit = Produits::find($id);

        if (!$produit) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }

        $request->validate([
            'designation' => 'required',
            'description' => 'required',
            'prix' => 'required|numeric',
            'stock' => 'required|numeric',
            'type' => 'required',
            'couleur' => 'nullable|string',
            'reduction' => 'nullable|numeric',
        ]);

        $produit->designation = $request->input('designation');
        $produit->description = $request->input('description');
        $produit->prix = $request->input('prix');
        $produit->stock = $request->input('stock');
        $produit->type = $request->input('type');
        $produit->couleur = $request->input('couleur');
        $produit->reduction = $request->input('reduction');

        $produit->save();

        return response()->json(['success' => true]);
    }
}
