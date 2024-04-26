<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    public function query(Request $request)
    {
        $query = $request->get("query");
        $all_chocolates = [];
        $apiUrl = "https://chocolaterie-vmod4mzmzq-uc.a.run.app/api/chocolates?limit=20";
        $client = new Client();
        $response = $client->request('GET', $apiUrl);

        $chocolat = json_decode($response->getBody()->getContents());
    }
}


