<?php
namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    public function handle($request, Closure $next, $role)
{
    if (!auth()->check() || auth()->user()->role !== $role) {
        if (auth()->check()) {
            // Redirige basado en el rol del usuario
            if (auth()->user()->role === 'worker') {
                return redirect()->route('worker.dashboard');
            }
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }
        return redirect('login');
    }
    return $next($request);
}
}