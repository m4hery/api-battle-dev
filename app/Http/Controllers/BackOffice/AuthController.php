<?php

namespace App\Http\Controllers\BackOffice;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
            if($user->role !== "admin") return response()->json([
                'code'      =>  403,
                'message'   =>  "Vous n'êtes pas autorisé à accéder à cette ressource"
            ], 401);
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
}
