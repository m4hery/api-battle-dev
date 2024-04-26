<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommandeController extends Controller
{
    // protected function checkBonachat

    public function getCommandes(Request $request)
    {
        $commandes = Commande::where('user_id', $request->user->id)->get();
        $data = [];
        foreach ($commandes as $commande) {
            $data[] = $commande->info_commande_group;
        }

        return response()->json($data);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $commandes = Commande::where('user_id', $request->user->id)->get();
        $data = [];
        foreach ($commandes as $commande) {
            $data[] = $commande->info_commande;
        }

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $vs = Validator::make($request->all(), [
            'paniers' => 'required|array',
            'paniers.*.nom' => 'required|string',
            'paniers.*.quantity' => 'required|integer',
            'paniers.*.totalPrice' => 'required|numeric',
        ]);

        if ($vs->fails()) {
            return response()->json($vs->errors(), 422);
        }

        $commande = Commande::create([
            'user_id' => $request->user->id,
            'dateOfCommand' => now(),
            "ref" => "CMD-" . time(),
        ]);

        foreach ($request->paniers as $panier) {
           $commande->chocolatCommandes()->create([
               'chocolat_nom' => $panier['nom'],
               'quantity' => $panier['quantity'],
               'totalPrice' => $panier['totalPrice'],
           ]);
        }

        if($request->has('ref'))
        {
            $commande_source = Commande::where('ref', $request->ref)->first();
            $user_source = $commande_source->user;
            
            
        }

        return response()->json($commande->info_commande);
    }

    /**
     * Display the specified resource.
     */
    public function show(Commande $commande)
    {
        return response()->json($commande->info_commande);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commande $commande)
    {
        $commande->delete();
        return response()->json(['message' => 'Commande supprimée avec succès']);
    }

    public function getProduitByRef($ref)
    {
        $commande = Commande::where('ref', $ref)->first();
        return response()->json($commande->info_produit);
    }
}
