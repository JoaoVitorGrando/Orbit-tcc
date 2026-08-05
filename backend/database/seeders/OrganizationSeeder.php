<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Duas organizações fixas, para demonstrar isolamento multitenant real
     * (dados de uma nunca aparecem para a outra) já na massa de demonstração.
     */
    public function run(): void
    {
        Organization::create([
            'name' => 'Padaria Trigo Dourado',
            'timezone' => 'America/Sao_Paulo',
        ]);

        Organization::create([
            'name' => 'Vetta Contabilidade',
            'timezone' => 'America/Sao_Paulo',
        ]);
    }
}
