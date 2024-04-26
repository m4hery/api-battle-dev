<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAutenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization');
        
        if (!is_null($header)) {
            $token = str_replace("Bearer ", "", $header);

            $data = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            $user = User::find($data->id);

            if (is_null($user)) {
                return response("Vous n'êtes pas autorisé", 403);
            }

            $request->merge(['user' => $user]);
            
            return $next($request);
            
        }

        return response("Vous n'êtes pas autorisé", 403);
    }
}
