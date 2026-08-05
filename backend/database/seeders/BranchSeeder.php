<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Três filiais: duas na Padaria Trigo Dourado (demonstra RF07 — busca por
     * competência atravessando filiais da mesma organização) e uma na Vetta
     * Contabilidade (demonstra que essa busca nunca atravessa organizações).
     *
     * Busca as organizações pelo nome em vez de assumir o id — não depende
     * da ordem de execução do OrganizationSeeder permanecer 1/2.
     */
    public function run(): void
    {
        $padaria = Organization::where('name', 'Padaria Trigo Dourado')->firstOrFail();
        $vetta = Organization::where('name', 'Vetta Contabilidade')->firstOrFail();

        Branch::create([
            'organization_id' => $padaria->id,
            'name' => 'Matriz - Centro',
        ]);

        Branch::create([
            'organization_id' => $padaria->id,
            'name' => 'Filial - Zona Sul',
        ]);

        Branch::create([
            'organization_id' => $vetta->id,
            'name' => 'Matriz',
        ]);
    }
}
