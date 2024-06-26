<?php

namespace App\Http\Controllers\BackOffice;

use App\Models\Commande;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class StatistiqueController extends Controller
{
    protected function getChocolats ()
    {
        $apiUrl = "https://chocolaterie-vmod4mzmzq-uc.a.run.app/api/chocolates?limit=20";
        $client = new Client();
        $response = $client->request('GET', $apiUrl);
        $chocolats = json_decode($response->getBody()->getContents());

        return $chocolats->data;
        
    }

    public function venteProduit ()
    {
        $client = new Client();

        $commandes = Commande::all();
        $data = [];
        $trueData = [];
        $labels = [];
        foreach ($commandes as $commande) {
            foreach ($commande->chocolatCommandes as $chocolatCommande) {
                if (array_key_exists($chocolatCommande->chocolat_nom, $data)) {
                    $data[$chocolatCommande->chocolat_nom] += $chocolatCommande->quantity;
                } else {
                    $data[$chocolatCommande->chocolat_nom] = $chocolatCommande->quantity;
                }
            }
        }
        foreach ($data as $key => $value) {
            $labels[] = $key;
            $trueData[] = $value;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $trueData,
        ]);
    }
    

    public function venteCategories ()
    {
        $commandes = Commande::all();
        $data = [];
        $trueData = [];
        $labels = [];
        foreach ($commandes as $commande) {
            
            foreach ( $commande->info_commande_group[0]["products"] as $produit) {
                // return $produit;
                if (array_key_exists($produit['categorie'], $data)) {
                    $data[$produit['categorie']] += $produit['quantity'];
                } else {
                    $data[$produit['categorie']] = $produit['quantity'];
                }
            }
        }
        foreach ($data as $key => $value) {
            $labels[] = $key;
            $trueData[] = $value;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $trueData,
        ]);
    }

    public function venteOrigines()
    {
        $commandes = Commande::all();
        $data = [];
        $trueData = [];
        $labels = [];
        foreach ($commandes as $commande) {
            
            foreach ( $commande->info_commande_group[0]["products"] as $produit) {
                // return $produit;
                if (array_key_exists($produit['origine'], $data)) {
                    $data[$produit['origine']] += $produit['quantity'];
                } else {
                    $data[$produit['origine']] = $produit['quantity'];
                }
            }
        }
        foreach ($data as $key => $value) {
            $labels[] = $key;
            $trueData[] = $value;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $trueData,
        ]);
    }

    public function venteSexe()
    {
        $commandes = Commande::all();
        $data = [];
        $trueData = [];
        $labels = [];
        foreach ($commandes as $commande) {
            $sexe = $commande->user->sexe;
            foreach ($commande->chocolatCommandes as $chocolatCommande) {
                if (array_key_exists($sexe, $data)) {
                    $data[$sexe] += $chocolatCommande->quantity;
                } else {
                    $data[$sexe] = $chocolatCommande->quantity;
                }
            }
        }
        foreach ($data as $key => $value) {
            $labels[] = $key;
            $trueData[] = $value;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $trueData,
        ]);
    }

    public function venteClient()
    {
        $commandes = Commande::all();
        $data = [];
        $trueData = [];
        $labels = [];
        foreach ($commandes as $commande) {
            $client = $commande->user->name;
            foreach ($commande->chocolatCommandes as $chocolatCommande) {
                if (array_key_exists($client, $data)) {
                    $data[$client] += $chocolatCommande->quantity;
                } else {
                    $data[$client] = $chocolatCommande->quantity;
                }
            }
        }
        foreach ($data as $key => $value) {
            $labels[] = $key;
            $trueData[] = $value;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $trueData,
        ]);
    }
}
