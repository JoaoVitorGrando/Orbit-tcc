<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * "Segurança na porta": barra a requisição antes dela chegar ao
 * controller, se o papel do usuário autenticado não estiver na lista
 * permitida pela rota. Uso: ->middleware('role:admin') ou
 * ->middleware('role:admin,gestor') para permitir mais de um papel.
 *
 * Front-end também esconde/bloqueia essas rotas na navegação (RNF01),
 * mas essa checagem aqui é a que realmente importa — o front nunca é
 * a única barreira.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Você não tem permissão para acessar este recurso.');
        }

        return $next($request);
    }
}
