<?php

namespace App\Http\Controllers;


use Stripe\Stripe;
use App\Models\Commande;
use Stripe\StripeClient;
use Illuminate\Http\Request;
use Stripe\Exception\CardException;
use Illuminate\Support\Facades\Validator;

class PayementController extends Controller
{
    public function storeStripe(Request $request)
    {
        $vs = Validator::make($request->all(), [
            'token' => 'required',
            'ref' => 'required',
        ]);

        if($vs->fails()) return response()->json(['errors' => $vs->errors()]);
        try
        {

            $stripe = new StripeClient(env('STRIPE_SECRET'));
            $commande = Commande::where('ref', $request->ref)->first();
            $mount = $commande->montant * 100;

            $response = $stripe->charges->create([
                'amount' => $mount,
                'currency' => 'eur',
                'source' => $request->token,
                'description' => 'Payement commande '.$request->reference_commande,
                'metadata' => [
                    'reference_commande' => $request->reference_commande,
                ],
            ]);

            if($response->paid) {
                $commande = Commande::where('reference', $request->reference_commande)->first();
                $commande->isPaid = true;
                $commande->charge_id = $response->id;
                $commande->montant = $response->amount / 100;
                $commande->montant_net = $this->getMontantNet($response);
                //$commande->fee = $this->getFee($response);
                $commande->save();
            }

            return response()->json([
                'success' => true,
                'status' => 200,
                // 'message' => $response->status,
                // 'facture' => $response->receipt_url
            ]);
        }
        catch(CardException $e) {
            // Since it's a decline, \Stripe\Exception\CardException will be caught
           return response()->json([
            'success' => false,
            'status' => $e->getHttpStatus(),
            'message' => $e->getError()->message ,
            ]);
          }
    }

}
