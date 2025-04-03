<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Compte;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Exception;

class Authentification
{
    public function handle(Request $request, Closure $next): Response
    {
        if (isset($_COOKIE["auth"])) {
            try {
                $cle = "T3mUjGjhC6WuxyNGR2rkUt2uQgrlFUHx";
                $jwt = JWT::decode($_COOKIE["auth"], new Key($cle, 'HS256'));
                $infosAuth = (array) $jwt;

                $compte = Compte::find($infosAuth["sub"]);
                if ($compte) {
                    $isAdmin = $compte->role === 'admin';
                    session(['isAdmin' => $isAdmin]);
                    return $next($request);
                }
            } catch (ExpiredException $e) {
                try {
                    // Décode le token expiré sans vérifier l'expiration
                    $decoded = base64_decode(explode('.', $_COOKIE["auth"])[1]);
                    $payload = json_decode($decoded, true);
                    
                    // Si le token était pour 30 jours, on le renouvelle
                    if ($payload['exp'] - $payload['iat'] > 3600) {
                        $compte = Compte::find($payload["sub"]);
                        if ($compte) {
                            // Génère un nouveau token de 30 jours
                            $newPayload = [
                                "iss" => "https://app.wisikard.fr",
                                "sub" => $compte->idCompte,
                                "iat" => time(),
                                "exp" => time() + (30 * 24 * 3600)
                            ];
                            $newJwt = JWT::encode($newPayload, $cle, 'HS256');
                            setcookie("auth", $newJwt, time() + (30 * 24 * 3600), "/", "", false, true);
                            
                            $isAdmin = $compte->role === 'admin';
                            session(['isAdmin' => $isAdmin]);
                            return $next($request);
                        }
                    }
                } catch (Exception $ex) {
                    // En cas d'erreur, on supprime le cookie et on redirige
                    setcookie("auth", "", time() - 3600);
                    return redirect()->to('connexion')->send();
                }
                
                // Si on ne peut pas renouveler, on supprime le cookie et on redirige
                setcookie("auth", "", time() - 3600);
                return redirect()->to('connexion')->send();
            } catch (Exception $ex) {
                setcookie("auth", "", time() - 3600);
                return redirect()->to('connexion')->send();
            }
        }
        
        return redirect()->to('connexion')->send();
    }
}
