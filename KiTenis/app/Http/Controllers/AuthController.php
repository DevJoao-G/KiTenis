<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /* =========================
       REGISTER
    ========================= */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // 🔥 LOGIN AUTOMÁTICO (SESSION)
        Auth::login($user);

        return response()->json([
            'message' => 'Conta criada com sucesso',
        ], 201);
    }

    /* =========================
       LOGIN
    ========================= */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return response()->json([
                'message' => 'Credenciais inválidas',
            ], 422);
        }

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login realizado com sucesso',
        ]);
    }
}
