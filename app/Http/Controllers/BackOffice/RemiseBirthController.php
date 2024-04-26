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
    public function show(RemiseBirth $remiseBirth)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RemiseBirth $remiseBirth)
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

        $remiseBirth->update($request->all());

        return response()->json($remiseBirth, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RemiseBirth $remiseBirth)
    {
        $remiseBirth->delete();

        return response()->json(null, 204);
    }
}
