<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Se o teste não chamar ->for($org), a factory cria uma
            // organização nova sozinha — cada teste continua isolado.
            'organization_id' => Organization::factory(),
            'branch_id' => null,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => 'colaborador',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'admin']);
    }

    public function gestor(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'gestor']);
    }

    public function colaborador(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'colaborador']);
    }
}
