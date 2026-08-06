<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Autentica por e-mail e senha, devolve um token Bearer.
     * Sanctum em modo token (não cookie): front-end e API vivem em origens
     * diferentes em desenvolvimento, então o React guarda e reenvia o token
     * a cada requisição — sem depender de sessão/cookie compartilhado.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais inválidas.'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();
        $token = $user->createToken('orbit-rh')->plainTextToken;

        return response()->json([
            'user' => $user->only(['id', 'name', 'email', 'role', 'organization_id', 'branch_id']),
            'token' => $token,
        ]);
    }

    /**
     * Revoga apenas o token usado nesta requisição — não desconecta
     * o usuário de outros dispositivos/abas onde ele tenha logado.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sessão encerrada.']);
    }

    public function me(Request $request)
    {
        return $request->user()->only(['id', 'name', 'email', 'role', 'organization_id', 'branch_id']);
    }
}
