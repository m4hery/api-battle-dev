<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function chocolatCommandes()
    {
        return $this->hasMany(ChocolatCommande::class);
    }

    public function getInfoProduitAttribute()
    {
        $product = [];
        $apiUrl = "https://chocolaterie-vmod4mzmzq-uc.a.run.app/api/chocolates";
        $client = new Client();
        foreach ($this->chocolatCommandes as $chocolatCommande) {
            $response = $client->request('GET', $apiUrl . '?nom=' . $chocolatCommande->chocolat_nom);

            $chocolat = json_decode($response->getBody()->getContents());
            $chocolat = $chocolat->data[0];
            $product[] = [
                "chocolat_id" => $chocolat->chocolat_id,
                'nom' => $chocolatCommande->chocolat_nom,
                'description' => $chocolat->description,
                'prix' => $chocolat->prix,
                'categorie' => $chocolat->categorie,
                'origine' => $chocolat->origine,
                'image' => $chocolat->image,
                'categorie_id' => $chocolat->categorie_id,
                'origine_id' => $chocolat->origine_id,
                'totalPrice' => $chocolatCommande->totalPrice,
                'quantity' => $chocolatCommande->quantity,
             ];
        }
        return $product;
    }

    public function getInfoCommandeGroupAttribute()
    {
        $apiUrl = "https://chocolaterie-vmod4mzmzq-uc.a.run.app/api/chocolates";
        $product = [];
        $data = [];
        $client = new Client();
        foreach ($this->chocolatCommandes as $chocolatCommande) {
            $response = $client->request('GET', $apiUrl . '?nom=' . $chocolatCommande->chocolat_nom);

            $chocolat = json_decode($response->getBody()->getContents());
            $chocolat = $chocolat->data[0];
            $product[] = [
                "chocolat_id" => $chocolat->chocolat_id,
                'nom' => $chocolatCommande->chocolat_nom,
                'description' => $chocolat->description,
                'prix' => $chocolat->prix,
                'categorie' => $chocolat->categorie,
                'origine' => $chocolat->origine,
                'image' => $chocolat->image,
                'categorie_id' => $chocolat->categorie_id,
                'origine_id' => $chocolat->origine_id,
                'totalPrice' => $chocolatCommande->totalPrice,
                'quantity' => $chocolatCommande->quantity,
             ];
            }
            $data[] = [
               "id" => $this->id,
               "ref" => $this->ref,
               "dateOfCommand" => $this->dateOfCommand,
               "products" => $product,
               "isPaid" => $this->isPaid,
            ];

        return $data;
    }


    public function getInfoCommandeAttribute()
    {
        $apiUrl = "https://chocolaterie-vmod4mzmzq-uc.a.run.app/api/chocolates";
        $product = [];
        $data = [];
        $client = new Client();
        foreach ($this->chocolatCommandes as $chocolatCommande) {
            $response = $client->request('GET', $apiUrl . '?nom=' . $chocolatCommande->chocolat_nom);

            $chocolat = json_decode($response->getBody()->getContents());
            $chocolat = $chocolat->data[0];
            $product = [
                "chocolat_id" => $chocolat->chocolat_id,
                'nom' => $chocolatCommande->chocolat_nom,
                'description' => $chocolat->description,
                'prix' => $chocolat->prix,
                'categorie' => $chocolat->categorie,
                'origine' => $chocolat->origine,
                'image' => $chocolat->image,
                'categorie_id' => $chocolat->categorie_id,
                'origine_id' => $chocolat->origine_id,
                'totalPrice' => $chocolatCommande->totalPrice,
                'quantity' => $chocolatCommande->quantity,
             ];
             $data[] = [
                "id" => $this->id,
                "ref" => $this->ref,
                "dateOfCommand" => $this->dateOfCommand,
                "product" => $product,
                "isPaid" => $this->isPaid,
             ];
        }

        return $data;
    }
}
