<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Response)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifiez si l'utilisateur est administrateur
        if (session('isAdmin') !== true) {
            return redirect()->to('connexion')->withErrors('Accès interdit : Interdit aux non-administrateurs.');
        }

        return $next($request);
    }
}