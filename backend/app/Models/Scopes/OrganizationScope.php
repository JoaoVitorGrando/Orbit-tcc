<?php

namespace App\Models\Scopes;

use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Camada 1 do isolamento multitenant (RNF02).
 *
 * Acrescenta `where organization_id = ?` a toda consulta dos models que
 * usam o trait BelongsToOrganization, sem que quem escreve a consulta
 * precise lembrar disso.
 *
 * Quando não há tenant definido no contexto — comando de terminal,
 * seeder, tarefa administrativa — o escopo não filtra. Essa é uma decisão
 * consciente: em contexto administrativo o acesso amplo é legítimo. É
 * justamente por isso que a camada 2 (Row Level Security, no PostgreSQL)
 * existe e é independente desta.
 */
class OrganizationScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $tenant = app(TenantContext::class);

        if (! $tenant->hasTenant()) {
            return;
        }

        $builder->where(
            $model->qualifyColumn('organization_id'),
            $tenant->organizationId()
        );
    }
}
