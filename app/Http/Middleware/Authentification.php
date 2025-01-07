<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Compte;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Authentification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (isset($_COOKIE["auth"])) {
            try {
                $cle = "T3mUjGjhC6WuxyNGR2rkUt2uQgrlFUHx";
                $jwt = JWT::decode($_COOKIE["auth"], new Key($cle, 'HS256'));
                $infosAuth = (array) $jwt;

                $compte = Compte::find($infosAuth["sub"]);
                if ($compte) {
                    // Check if the user is an admin
                    $isAdmin = $compte->role === 'admin';
                    session(['isAdmin' => $isAdmin]);

                    return $next($request);
                } else {
                    setcookie("auth", "", time() - 3600);
                    return redirect()->to('connexion')->send();
                }
            } catch (Exception $ex) {
                setcookie("auth", "", time() - 3600);
                return redirect()->to('connexion')->send();
            }
        } else {
            return redirect()->to('connexion')->send();
        }
    }
}
