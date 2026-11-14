<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole {
    public function handle(Request $request, Closure $next, ...$roles) {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }

        // Jika role tidak sesuai, kembalikan ke dashboard role masing-masing
        $role = Auth::user()->role;
        return redirect()->route($role . '.dashboard');
    }
}