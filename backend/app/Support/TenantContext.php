<?php

namespace App\Support;

use App\Models\User;

/**
 * Contexto de tenant da requisição em curso.
 *
 * Guarda a resposta para "de qual organização e filial são os dados que
 * podem ser acessados agora". É preenchido uma única vez, logo após a
 * autenticação, e consultado dali em diante.
 *
 * Existe como objeto próprio, e não como consulta direta a `auth()`, por
 * três razões:
 *
 * 1. Funciona fora do ciclo de requisição HTTP — comandos de terminal,
 *    tarefas em fila e testes não têm usuário autenticado, mas ainda
 *    precisam operar sob um tenant definido.
 * 2. Separa persistência de autenticação: o filtro de dados não precisa
 *    conhecer o mecanismo de login.
 * 3. **Torna as duas camadas de isolamento independentes.** O Global Scope
 *    (camada 1) e a Row Level Security (camada 2) leem daqui. Desativar
 *    uma não afeta a outra, o que é condição para o teste de defesa em
 *    profundidade ser conclusivo.
 *
 * Registrado como singleton no container: uma instância por requisição.
 */
class TenantContext
{
    private ?int $organizationId = null;

    private ?int $branchId = null;

    private ?string $role = null;

    public function fromUser(User $user): void
    {
        $this->organizationId = $user->organization_id;
        $this->branchId = $user->branch_id;
        $this->role = $user->role;
    }

    public function set(?int $organizationId, ?int $branchId = null, ?string $role = null): void
    {
        $this->organizationId = $organizationId;
        $this->branchId = $branchId;
        $this->role = $role;
    }

    public function clear(): void
    {
        $this->organizationId = null;
        $this->branchId = null;
        $this->role = null;
    }

    public function organizationId(): ?int
    {
        return $this->organizationId;
    }

    public function branchId(): ?int
    {
        return $this->branchId;
    }

    public function role(): ?string
    {
        return $this->role;
    }

    public function hasTenant(): bool
    {
        return $this->organizationId !== null;
    }

    /**
     * O administrador enxerga todas as filiais da própria organização —
     * o escopo de filial não se aplica a ele. O isolamento por
     * organização continua valendo, sem exceção de papel.
     */
    public function isRestrictedToBranch(): bool
    {
        return $this->branchId !== null && $this->role !== User::ROLE_ADMIN;
    }
}
