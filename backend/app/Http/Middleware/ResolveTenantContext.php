<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Preenche o contexto de tenant a partir do usuário autenticado.
 *
 * Precisa rodar DEPOIS da autenticação (`auth:sanctum`) e ANTES de
 * qualquer consulta ao banco. A organização vem sempre do registro do
 * usuário no servidor — nunca de cabeçalho, parâmetro de rota ou corpo
 * da requisição, que são controlados pelo cliente.
 */
class ResolveTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            app(TenantContext::class)->fromUser($user);
        }

        return $next($request);
    }
}
