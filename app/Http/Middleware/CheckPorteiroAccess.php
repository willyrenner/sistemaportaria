<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPorteiroAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role === 'porteiro') {
            // Rotas permitidas
            $allowedRoutes = ['porteiro.dashboard', 'profile.edit', 'profile.update', 'profile.destroy'];

            // Nome da rota atual
            $currentRouteName = $request->route()->getName();

            // Verificar se a rota atual não está na lista permitida
            if (!$currentRouteName || !in_array($currentRouteName, $allowedRoutes)) {
                return redirect()->route('porteiro.dashboard')
                    ->with('error', 'Você não tem permissão para acessar esta área.');
            }
        }
        return $next($request);
    }
}
