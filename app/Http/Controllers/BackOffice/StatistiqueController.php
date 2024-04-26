<?php

namespace App\Http\Controllers\BackOffice;

use App\Models\Commande;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatistiqueController extends Controller
{
    public function venteProduit ()
    {
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



}
