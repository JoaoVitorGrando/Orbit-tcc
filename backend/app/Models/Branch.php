<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
    ];

    /**
     * A filial é a própria unidade de segregação: não faz sentido
     * filtrá-la por `branch_id`, coluna que ela não possui. O isolamento
     * por organização continua aplicado.
     */
    protected static function aplicaEscopoDeFilial(): bool
    {
        return false;
    }

    // A relação `organization()` vem do trait BelongsToOrganization.

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
