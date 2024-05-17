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

    public function store (Request $request) {
        $validatedData = $request->validate([
            'designation' => 'required',
            'description' => 'required',
            'prix' => 'required|numeric',
            'stock' => 'required|numeric',
            'type' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation de l'image
        ]);

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);

            // Création du produit avec les données et le nom d'image
            Produits::create([
                'designation' => $validatedData['designation'],
                'description' => $validatedData['description'],
                'prix' => $validatedData['prix'],
                'stock' => $validatedData['stock'],
                'type' => $validatedData['type'],
                'image' => $imageName,
            ]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

    public function storea(Request $request) {
        return response()->json(['Uploaded' => true]);
    }

    public function show($id) {
        $produit = Produits::find($id);
    
        if (!$produit) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }
    
        return response()->json($produit);
    }
    
}
