<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commandes;
use App\Models\LigneCommandes;
use App\Models\Produits;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CommandesController extends Controller
{

    public function getAllCommand()
    {
        $commandes = Commandes::all();
        $currentDateTime = Carbon::now();
        return response()->json($currentDateTime);
    }

    public function commandeUser(Request $request)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = $request->input('user_id');

        $commandes = Commandes::with('ligneCommandes')->where('user_id', $userId)->get();

        if ($commandes->isEmpty()) {
            return response()->json(['message' => 'Aucune commande trouvée pour cet utilisateur.'], 404);
        }

        $formattedCommands = $commandes->map(function ($commande) {
            return [
                'id' => $commande->id,
                'date' => $commande->date_commande,
                'prix_commande' => $commande->prix_commande,
                'lines' => $commande->ligneCommandes->map(function ($ligne) {
                    return [
                        'id' => $ligne->id,
                        'article' => $ligne->article,
                        'qte' => $ligne->qte,
                        'prix' => $ligne->prix_article
                    ];
                })
            ];
        });

        return response()->json($formattedCommands);
    }



    public function store(Request $request)
    {
        // Validation des données de la requête
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'prix_commande' => 'required|numeric',
        ]);

        // Création de la commande
        $commande = Commandes::create([
            'user_id' => $request->user_id,
            'date_commande' => now(),
            'prix_commande' => $request->prix_commande,
        ]);

        return response()->json($commande, 201);
    }


    public function storeDetail(Request $request)
    {
        // Validation des données de la requête
        $request->validate([
            '*.id_com' => 'required|exists:commandes,id',
            '*.article' => 'required',
            '*.qte' => 'required|numeric',
            '*.prix_article' => 'required|numeric',
        ]);

        // Créer les détails de la commande pour chaque élément du tableau
        $details = collect($request->all())->map(function ($detail) {
            $article = Produits::where('designation', $detail['article'])->first();
            $article->stock -= $detail['qte'];
            $article->save();

            return LigneCommandes::create([
                'id_com' => $detail['id_com'],
                'article' => $detail['article'],
                'qte' => $detail['qte'],
                'prix_article' => $detail['prix_article'],
            ]);
        });

        return response()->json(['message' => 'Articles enregistrés avec succès'], 201);
    }

    public function mostPurchased()
    {
        $articlesPlusVendus = LigneCommandes::select('produits.id', 'designation', 'description', 'prix', 'stock', 'type', 'image', DB::raw('SUM(ligne_commandes.qte) AS total_achete'))
            ->join('produits', 'ligne_commandes.article', '=', 'produits.designation')
            ->groupBy('ligne_commandes.article', 'produits.id', 'designation', 'description', 'prix', 'stock', 'type', 'image')
            ->orderByRaw('SUM(ligne_commandes.qte) DESC')
            ->limit(4)
            ->get();

        return response()->json($articlesPlusVendus);
    }

    public function topProducts()
    {
        $articlesPlusVendus = LigneCommandes::select('produits.id', 'designation', 'description', 'prix', 'stock', 'type', 'image', DB::raw('SUM(ligne_commandes.qte) AS total_achete'))
            ->join('produits', 'ligne_commandes.article', '=', 'produits.designation')
            ->groupBy('ligne_commandes.article', 'produits.id', 'designation', 'description', 'prix', 'stock', 'type', 'image')
            ->orderByRaw('SUM(ligne_commandes.qte) DESC')
            ->limit(1)
            ->get();

        return response()->json($articlesPlusVendus);
    }


    public function getDailyTotalPrices(Request $request)
    {
        $results = DB::table('commandes')
            ->select(DB::raw('DATE(date_commande) as date'), DB::raw('SUM(prix_commande) as total_prix_commande'))
            ->groupBy(DB::raw('DATE(date_commande)'))
            ->orderBy(DB::raw('DATE(date_commande)'))
            ->get();

        return response()->json($results);
    }

    public function topClients()
    {
        $topClients = DB::table('users as u')
            ->join('commandes as c', 'u.id', '=', 'c.user_id')
            ->join('ligne_commandes as lc', 'c.id', '=', 'lc.id_com')
            ->select('u.id as user_id', 'u.name as user_name', DB::raw('SUM(lc.qte) as total_articles'))
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('total_articles')
            ->limit(5)
            ->get();

        return response()->json($topClients);
    }

    public function topOneClients()
    {
        $topClients = DB::table('users as u')
            ->join('commandes as c', 'u.id', '=', 'c.user_id')
            ->join('ligne_commandes as lc', 'c.id', '=', 'lc.id_com')
            ->select('u.id as user_id', 'u.name as user_name', DB::raw('SUM(lc.qte) as total_articles'))
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('total_articles')
            ->limit(1)
            ->get();

        return response()->json($topClients);
    }
}
