<?php

namespace App\Models\Scopes;

use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Segregação por filial.
 *
 * Diferente do OrganizationScope em um ponto essencial: este escopo
 * depende do papel de quem consulta. O Administrador supervisiona todas
 * as filiais da própria organização, então para ele o filtro não se
 * aplica. Gestor e colaborador ficam restritos à filial em que estão
 * lotados.
 *
 * Essa dependência de papel torna o escopo mais difícil de raciocinar do
 * que o de organização, e por isso está isolado em uma classe própria,
 * com a condição concentrada em `TenantContext::isRestrictedToBranch()`.
 *
 * **Exceção documentada (RF07).** A busca de colaboradores por
 * competência atravessa filiais deliberadamente, para apoiar mobilidade
 * interna. Essa consulta remove este escopo de forma explícita, por meio
 * de `semEscopoDeFilial()`. A remoção é sempre visível no código que a
 * faz — nunca implícita. O escopo de organização permanece aplicado
 * mesmo nesse caso: a busca cruza filiais, nunca organizações.
 */
class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $tenant = app(TenantContext::class);

        if (! $tenant->isRestrictedToBranch()) {
            return;
        }

        $builder->where(
            $model->qualifyColumn('branch_id'),
            $tenant->branchId()
        );
    }
}
