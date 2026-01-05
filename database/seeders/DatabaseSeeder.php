<?php

namespace Database\Seeders;

use App\Enums\BillingCycle;
use App\Enums\ServiceType;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user from .env
        $this->call(AdminUserSeeder::class);
        // Crear servicios básicos
        $servicioMantenimiento = Service::create([
            'name' => 'Mantenimiento Web Estándar',
            'type' => ServiceType::RECURRING,
            'base_price' => 50.00,
            'description' => 'Mantenimiento mensual de sitio web WordPress',
            'is_active' => true,
        ]);

        $servicioSoporte = Service::create([
            'name' => 'Soporte TI por Horas',
            'type' => ServiceType::HOURLY,
            'base_price' => 10.00,
            'description' => 'Soporte técnico y gestión de infraestructura',
            'is_active' => true,
        ]);

        // Crear 5 clientes de prueba
        Client::factory(5)->create()->each(function ($client) use ($servicioMantenimiento, $servicioSoporte) {
            // Crear contacto principal
            Contact::create([
                'client_id' => $client->id,
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'password' => 'password',
                'is_primary' => true,
            ]);

            // Algunos clientes tienen suscripción de mantenimiento
            if (rand(0, 1)) {
                Subscription::create([
                    'client_id' => $client->id,
                    'service_id' => $servicioMantenimiento->id,
                    'custom_price' => null, // Usa base_price
                    'billing_cycle' => BillingCycle::MONTHLY,
                    'next_billing_date' => now()->addMonth(),
                    'started_at' => now(),
                    'status' => 'active',
                ]);
            }
        });
    }
}