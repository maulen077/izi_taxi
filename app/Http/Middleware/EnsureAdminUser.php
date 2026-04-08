<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login.form');
        }

        $user = Auth::user();
        $role = $user?->role instanceof UserRole ? $user->role->value : (string) $user?->role;

        if ($role !== UserRole::Admin->value) {
            Auth::logout();

            return redirect()->route('admin.login.form')
                ->withErrors(['email' => 'У вас нет доступа']);
        }

        return $next($request);
    }
}
