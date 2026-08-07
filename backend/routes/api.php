<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // throttle:5,1 — no máximo 5 tentativas por minuto, por IP.
    // Sem isso, a rota de login aceita tentativas ilimitadas e fica
    // exposta a ataque de força bruta sobre as senhas.
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1');

    // `tenant` roda depois de `auth:sanctum`: precisa do usuário já
    // autenticado para descobrir a organização, e precisa estar definido
    // antes de qualquer consulta ao banco.
    Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});
