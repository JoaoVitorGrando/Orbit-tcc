<?php

namespace App\Models\Concerns;

use App\Models\Organization;
use App\Models\Scopes\BranchScope;
use App\Models\Scopes\OrganizationScope;
use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Aplica o isolamento multitenant (camada 1) ao model.
 *
 * Todo model de dado de negócio deve usar este trait. Ele faz três
 * coisas:
 *
 * 1. Filtra automaticamente toda consulta por `organization_id`, e por
 *    `branch_id` quando o model tiver essa coluna e o papel exigir.
 * 2. Preenche `organization_id` (e `branch_id`) automaticamente ao criar
 *    um registro, evitando que uma linha nasça órfã de tenant.
 * 3. Expõe a relação com a organização.
 *
 * O passo 2 é tão importante quanto o 1: um registro criado sem
 * `organization_id` correto ficaria invisível para todos ou, pior,
 * visível para quem não deveria.
 */
trait BelongsToOrganization
{
    public static function bootBelongsToOrganization(): void
    {
        static::addGlobalScope(new OrganizationScope);

        if (static::aplicaEscopoDeFilial()) {
            static::addGlobalScope(new BranchScope);
        }

        static::creating(function ($model) {
            $tenant = app(TenantContext::class);

            if (! $tenant->hasTenant()) {
                return;
            }

            if ($model->organization_id === null) {
                $model->organization_id = $tenant->organizationId();
            }

            if (static::aplicaEscopoDeFilial()
                && $model->branch_id === null
                && $tenant->branchId() !== null) {
                $model->branch_id = $tenant->branchId();
            }
        });
    }

    /**
     * Models sem coluna `branch_id` (por exemplo, `badges`, que é
     * catálogo da organização inteira) sobrescrevem isto para `false`.
     */
    protected static function aplicaEscopoDeFilial(): bool
    {
        return true;
    }

    /**
     * Remove apenas a segregação por filial, preservando a de
     * organização. Uso restrito e documentado: RF07, busca de
     * colaboradores por competência entre filiais.
     *
     * O nome é deliberadamente explícito. Toda chamada a este método é um
     * ponto do sistema em que uma barreira é retirada de propósito, e
     * precisa ser visível em revisão de código.
     */
    public function scopeSemEscopoDeFilial(Builder $query): Builder
    {
        return $query->withoutGlobalScope(BranchScope::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
