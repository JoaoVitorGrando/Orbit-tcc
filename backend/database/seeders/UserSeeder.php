<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * 20 usuários fixos: 2 administradores, 3 gestores (um por filial) e
     * 15 colaboradores. Nomes e e-mails são explícitos, não gerados por
     * Faker — os 8 voluntários do teste de usabilidade precisam encontrar
     * exatamente o mesmo estado a cada execução do seeder.
     *
     * Senha única para toda a massa de demonstração: 12345678.
     */
    public function run(): void
    {
        $padaria = Organization::where('name', 'Padaria Trigo Dourado')->firstOrFail();
        $vetta = Organization::where('name', 'Vetta Contabilidade')->firstOrFail();

        $matrizCentro = Branch::where('name', 'Matriz - Centro')->firstOrFail();
        $filialZonaSul = Branch::where('name', 'Filial - Zona Sul')->firstOrFail();
        $matrizVetta = Branch::where('name', 'Matriz')
            ->where('organization_id', $vetta->id)->firstOrFail();

        $senha = Hash::make('12345678');

        // Administradores — sem branch_id: supervisionam todas as filiais
        // da própria organização, não uma filial específica.
        User::create([
            'organization_id' => $padaria->id,
            'branch_id' => null,
            'name' => 'Administrador Geral',
            'email' => 'admin@demo.local',
            'password' => $senha,
            'role' => 'admin',
        ]);

        User::create([
            'organization_id' => $vetta->id,
            'branch_id' => null,
            'name' => 'Administradora Vetta',
            'email' => 'admin2@demo.local',
            'password' => $senha,
            'role' => 'admin',
        ]);

        // Gestores — um por filial.
        User::create([
            'organization_id' => $padaria->id,
            'branch_id' => $matrizCentro->id,
            'name' => 'Rafael Andrade Monteiro',
            'email' => 'gestor@demo.local',
            'password' => $senha,
            'role' => 'gestor',
        ]);

        User::create([
            'organization_id' => $padaria->id,
            'branch_id' => $filialZonaSul->id,
            'name' => 'Sabrina Lopes Guimarães',
            'email' => 'gestor2@demo.local',
            'password' => $senha,
            'role' => 'gestor',
        ]);

        User::create([
            'organization_id' => $vetta->id,
            'branch_id' => $matrizVetta->id,
            'name' => 'Thiago Machado Pereira',
            'email' => 'gestor3@demo.local',
            'password' => $senha,
            'role' => 'gestor',
        ]);

        // Colaboradores — 6 na Matriz-Centro, 5 na Filial-Zona Sul, 4 na Vetta.
        $colaboradoresMatrizCentro = [
            'colaborador@demo.local' => 'Ana Beatriz Souza',
            'colaborador2@demo.local' => 'Bruno Carvalho Lima',
            'colaborador3@demo.local' => 'Camila Ferreira Dias',
            'colaborador4@demo.local' => 'Diego Martins Alves',
            'colaborador5@demo.local' => 'Eduarda Ribeiro Santos',
            'colaborador6@demo.local' => 'Felipe Augusto Rocha',
        ];

        $colaboradoresFilialZonaSul = [
            'colaborador7@demo.local' => 'Gabriela Nunes Costa',
            'colaborador8@demo.local' => 'Henrique Barbosa Melo',
            'colaborador9@demo.local' => 'Isabela Cardoso Pinto',
            'colaborador10@demo.local' => 'João Pedro Teixeira',
            'colaborador11@demo.local' => 'Larissa Gomes Araújo',
        ];

        $colaboradoresVetta = [
            'colaborador12@demo.local' => 'Marcelo Duarte Moreira',
            'colaborador13@demo.local' => 'Natália Correia Batista',
            'colaborador14@demo.local' => 'Otávio Fernandes Ramos',
            'colaborador15@demo.local' => 'Patrícia Vieira Cunha',
        ];

        foreach ($colaboradoresMatrizCentro as $email => $nome) {
            User::create([
                'organization_id' => $padaria->id,
                'branch_id' => $matrizCentro->id,
                'name' => $nome,
                'email' => $email,
                'password' => $senha,
                'role' => 'colaborador',
            ]);
        }

        foreach ($colaboradoresFilialZonaSul as $email => $nome) {
            User::create([
                'organization_id' => $padaria->id,
                'branch_id' => $filialZonaSul->id,
                'name' => $nome,
                'email' => $email,
                'password' => $senha,
                'role' => 'colaborador',
            ]);
        }

        foreach ($colaboradoresVetta as $email => $nome) {
            User::create([
                'organization_id' => $vetta->id,
                'branch_id' => $matrizVetta->id,
                'name' => $nome,
                'email' => $email,
                'password' => $senha,
                'role' => 'colaborador',
            ]);
        }
    }
}
