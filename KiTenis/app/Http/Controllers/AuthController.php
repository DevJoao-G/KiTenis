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
            'user' => $user,
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
            'user' => Auth::user(),
        ]);
    }

    /* =========================
       LOGOUT
    ========================= */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout realizado com sucesso'
        ], 200);
    }

    /* =========================
       GET AUTHENTICATED USER
    ========================= */
    public function me(Request $request)
    {
        try {
            if (Auth::check()) {
                return response()->json(Auth::user());
            }

            return response()->json(['message' => 'Não autenticado'], 401);
        } catch (\Exception $e) {
            \Log::error('Erro no método me(): ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar usuário'], 500);
        }
    }
}