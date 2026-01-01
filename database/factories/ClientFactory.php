<?php

namespace Database\Factories;

use App\Enums\ClientStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_name' => fake()->company(),
            'tax_id' => fake()->unique()->numerify('###-####-####'),
            'internal_notes' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(ClientStatus::cases()),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ClientStatus::ACTIVE,
        ]);
    }
}