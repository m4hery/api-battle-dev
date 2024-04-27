<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use GuzzleHttp\Promise\Create;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommandeController extends Controller
{
    protected function checkBonachat($montant)
    {
        $remise = 0;
        //si le montant est inferieur ou egale a 30000
        if ($montant <= 30000) {
            $remise = $montant * 0.1;
        } elseif ($montant > 30000 && $montant <= 50000) {
            $remise = $montant * 0.25;
        } elseif ($montant > 50000 && $montant <= 150000) {
            $remise = $montant * 0.3;
        } 

        return $remise;
    }


    public function getCommandes(Request $request)
    {
        $commandes = Commande::where('user_id', $request->user->id)->where("isGift", false)->get();
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
        $commandes = Commande::where('user_id', $request->user->id)->where("isGift", false)->get();
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
        // return response()->json($request->all());
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
            "ref" => "CMD-" . time() .$request->user->id,
        ]);

        foreach ($request->paniers as $panier) {
           $commande->chocolatCommandes()->create([
               'chocolat_nom' => $panier['nom'],
               'quantity' => $panier['quantity'],
               'totalPrice' => $panier['totalPrice'],
           ]);
        }

        if($request->has('ref') && $request->ref)
        {
            $commande_source = Commande::where('ref', $request->ref)->first();
            $user_source = $commande_source->user;
            if($user_source->id !== $request->user->id)
            {
                $montantTotalCommande = $commande->chocolatCommandes->sum('totalPrice');

                if((int)$montantTotalCommande < 200000 )
                {
                    $comm = Commande::create([
                        'user_id' => $user_source->id,
                        'dateOfCommand' => now(),
                        "ref" => "CMD-" . time() . $request->user->id,
                        "isPaid" => true,
                        "isGift" => true,
                    ]);
    
                    foreach ($commande_source->chocolatCommandes as $panier) {
                        $comm->chocolatCommandes()->create([
                            'chocolat_nom' => $panier->chocolat_nom,
                            'quantity' => $panier->quantity,
                            'totalPrice' => $panier->totalPrice,
                        ]);
                    }
                }
    
                $bon_achat = $this->checkBonachat($montantTotalCommande);
                if( isset($user_source->bon_achat))
                {
                    $user_source->bon_achat()->update([
                        'montant' => isset($user_source->bon_achat) ? $user_source->bon_achat->montant + $bon_achat : $bon_achat,
                    ]);
                } else {
                    $user_source->bon_achat()->create([
                        'montant' => $bon_achat,
                    ]);
                }    
            }
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
        $commande = Commande::where('ref', $ref)->where("isGift", false)->first();
        return response()->json($commande->info_produit);
    }

    public function getBonDachat(Request $request)
    {
        $bon_achat = $request->user->bon_achat;
        return response()->json([
            "montant" => isset($bon_achat) ? $bon_achat->montant : 0,
        ]);
    }

    public function resetBonDachat(Request $request)
    {
        $request->user->bon_achat()->update([
            'montant' => $request->montant,
        ]);

        return response()->json([
            "montant" => 0,
        ]);
    }

    public function getGift(Request $request)
    {
        $commandes = Commande::where('user_id', $request->user->id)->where("isGift", true)->where("isGiftTake", false)->get();
        $data = [];
        foreach ($commandes as $commande) {
            $data = $commande->info_commande_group;
        }

        return response()->json($data);
    }

    public function reclameGift(Request $request)
    {
        return response()->json($request->all());
        $commande = Commande::where('ref', $request->ref)->first();
        
        $commande->update([
            'isPaid' => true,
            "isGiftTake" => true,
        ]);

        return response()->json($commande->info_commande_group);
    }
}
