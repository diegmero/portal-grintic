<?php

namespace Database\Factories;

use App\Enums\ServiceType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(ServiceType::cases());
        
        return [
            'name' => fake()->words(3, true),
            'type' => $type,
            'base_price' => fake()->randomFloat(2, 50, 500),
            'description' => fake()->optional()->paragraph(),
            'is_active' => true,
        ];
    }

    public function recurring(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ServiceType::RECURRING,
            'base_price' => fake()->randomElement([50, 100, 150, 200]),
        ]);
    }

    public function hourly(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ServiceType::HOURLY,
            'base_price' => fake()->randomElement([10, 15, 20, 25, 30]),
        ]);
    }
}