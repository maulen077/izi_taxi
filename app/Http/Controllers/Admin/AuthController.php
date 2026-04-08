<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($data['login']);
        $credentials = [
            'password' => $data['password'],
        ];

        if (str_contains($login, '@')) {
            $credentials['email'] = $login;
        } else {
            $credentials['phone'] = preg_replace('/\D+/', '', $login);
        }

        if (! Auth::attempt($credentials)) {
            return back()->withErrors([
                'login' => 'Неверный логин или пароль.',
            ])->withInput();
        }

        $user = Auth::user();
        $role = $user?->role instanceof UserRole ? $user->role->value : (string) $user?->role;

        if (! in_array($role, [UserRole::Admin->value, UserRole::Dispatcher->value], true)) {
            Auth::logout();

            return back()->withErrors([
                'login' => 'У вас нет доступа к панели.',
            ])->withInput();
        }

        $request->session()->regenerate();

        return redirect()->route($role === UserRole::Dispatcher->value ? 'dispatcher.main' : 'admin.main');
    }

    public function showRegister(): View
    {
        return view('admin.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30', 'unique:users,phone'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::query()->create([
            'name' => $data['name'],
            'phone' => preg_replace('/\D+/', '', $data['phone']),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserRole::Admin->value,
            'language' => 'ru',
            'driver_status' => 'offline',
            'balance' => 0,
            'trust_score' => 100,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('admin.main');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login.form');
    }
}
