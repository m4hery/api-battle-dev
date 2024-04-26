<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $vs = Validator::make($request->all(), [
            'email' => ['required'],
            'password' => ['required'],
        ]);


        if($vs->fails()) return response()->json($vs->errors(), 422);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

 
        if (Auth::attempt($credentials)) {
            $user = User::where("email", $credentials["email"])->first();
            $data =  $user->info_auth;
            $token = JWT::encode($data,env('JWT_SECRET'), 'HS256');
            return response()->json([
                "token" => $token
            ]);
        } 
 
        return response()->json([
            'code'      =>  401,
            'message'   =>  "Verifier votre email ou mot de passe"
        ], 401);
    }

    public function register(Request $request)
    {
        $vs = Validator::make($request->all(), [
            'nom' => "required",
            "email" => 'required|unique:users',
            "password" => 'required|confirmed'
        ]);

        if($vs->fails()) return response()->json($vs->errors(), 422);

        $user = User::create([
            "name" => $request->nom,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "telephone" => $request->telephone,
            "adresse" => $request->adresse
        ]);

        $data =  $user->info_auth;
        $token = JWT::encode($data,env('JWT_SECRET'), 'HS256');
        return response()->json([
            "token" => $token
        ]);
    }

    
}
