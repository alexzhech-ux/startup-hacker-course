<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'general' => 'Неверный логин или пароль',
            ]);
        }

        $request->session()->regenerate();
        return Inertia::location('/');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|unique:booksite_users,username',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $user = User::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role' => 1,
        ]);

        Auth::login($user);
        return Inertia::location('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Inertia::location('/');
    }
}