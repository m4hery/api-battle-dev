<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Remisearticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RemisearticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $remisearticles = Remisearticle::all();

        return response()->json($remisearticles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $vs = Validator::make($request->all(), [
            'nbr_article' => 'required|integer',
            'remise' => 'required|integer',
            'prix_min' => 'required|numeric',   
            'prix_max' => 'required|numeric',
            'signe_min' => 'required|string',
            'signe_max' => 'required|string',
        ]);

        if ($vs->fails()) {
            return response()->json($vs->errors(), 422);
        }

        $remisearticle = Remisearticle::create($request->all());

        return response()->json($remisearticle, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Remisearticle $remisearticle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Remisearticle $remisearticle)
    {
        $vs = Validator::make($request->all(), [
            'nbr_article' => 'required|integer',
            'remise' => 'required|integer',
            'prix_min' => 'required|numeric',   
            'prix_max' => 'required|numeric',
            'signe_min' => 'required|string',
            'signe_max' => 'required|string',
        ]);

        if ($vs->fails()) {
            return response()->json($vs->errors(), 422);
        }

        $remisearticle->update($request->all());

        return response()->json($remisearticle);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Remisearticle $remisearticle)
    {
        $remisearticle->delete();

        return response()->json(['message' => 'Remisearticle deleted']);
    }

    public function changeActif(Remisearticle $remisearticle)
    {
        $remisearticle->update([
            'isActif' => !$remisearticle->isActif
        ]);

        return response()->json($remisearticle);
    }
}
