<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePanelUser
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login.form');
        }

        $user = Auth::user();
        $role = $user?->role instanceof UserRole ? $user->role->value : (string) $user?->role;

        if ($roles !== [] && ! in_array($role, $roles, true)) {
            Auth::logout();

            return redirect()
                ->route('admin.login.form')
                ->withErrors(['login' => 'У вас нет доступа к этой панели.']);
        }

        return $next($request);
    }
}
