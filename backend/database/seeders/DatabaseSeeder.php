<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ordem importa: organizações antes de filiais, filiais antes de
     * usuários — cada seeder busca a entidade-pai pelo nome, não pelo id.
     */
    public function run(): void
    {
        $this->call([
            OrganizationSeeder::class,
            BranchSeeder::class,
            UserSeeder::class,
        ]);
    }
}
