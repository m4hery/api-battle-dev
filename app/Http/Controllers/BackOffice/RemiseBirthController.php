<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\RemiseBirth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RemiseBirthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $remiseBirths = RemiseBirth::all();

        return response()->json($remiseBirths);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $vs = Validator::make($request->all(), [
            'nbr_by_birth' => 'required|integer',
            'remise' => 'required',
            'typeDate' => 'required',   
            'quand' => 'required',
        ]);

        if ($vs->fails()) {
            return response()->json($vs->errors(), 400);
        }

        $remiseBirth = RemiseBirth::create($request->all());

        return response()->json($remiseBirth, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RemiseBirth $remise_birth)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RemiseBirth $remise_birth)
    {
        $vs = Validator::make($request->all(), [
            'nbr_by_birth' => 'required|integer',
            'remise' => 'required',
            'typeDate' => 'required',   
            'quand' => 'required',
        ]);

        if ($vs->fails()) {
            return response()->json($vs->errors(), 400);
        }

        $remise_birth->update($request->all());
        $remise_birth->fresh();
        return response()->json($remise_birth, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RemiseBirth $remise_birth)
    {
        $remise_birth->delete();

        return response()->json(null, 204);
    }
}
