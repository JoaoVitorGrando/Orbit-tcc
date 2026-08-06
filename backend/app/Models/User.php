<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_GESTOR = 'gestor';
    public const ROLE_COLABORADOR = 'colaborador';

    /** Exatamente três papéis (RF11). A lista é fechada por requisito. */
    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_GESTOR,
        self::ROLE_COLABORADOR,
    ];

    /**
     * Atribuíveis em massa.
     *
     * `role` e `organization_id` estão DELIBERADAMENTE fora desta lista.
     * Ambos são campos de privilégio: `role` define o que a pessoa pode
     * fazer, `organization_id` define quais dados ela enxerga. Se
     * estivessem aqui, qualquer endpoint que fizesse
     * `$user->update($request->validated())` — um "editar perfil", por
     * exemplo — permitiria que o próprio usuário se promovesse a admin
     * ou migrasse para outra organização, furando o isolamento
     * multitenant (RNF02/RNF03) por dentro.
     *
     * Esses dois campos só podem ser definidos por atribuição explícita,
     * em código que verificou a permissão antes. Ver `assignRole()` e
     * `assignToOrganization()` abaixo.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'name',
        'email',
        'password',
    ];

    /**
     * Único caminho para definir o papel. Explícito por desenho: quem
     * chama isto precisou passar por uma verificação de permissão antes.
     */
    public function assignRole(string $role): void
    {
        if (! in_array($role, self::ROLES, true)) {
            throw new \InvalidArgumentException("Papel inválido: {$role}");
        }

        $this->role = $role;
    }

    /**
     * Vincula o usuário a uma organização. Fora do fluxo de criação
     * administrativa, nada deve chamar isto.
     */
    public function assignToOrganization(int $organizationId): void
    {
        $this->organization_id = $organizationId;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isGestor(): bool
    {
        return $this->role === self::ROLE_GESTOR;
    }

    public function isColaborador(): bool
    {
        return $this->role === self::ROLE_COLABORADOR;
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
