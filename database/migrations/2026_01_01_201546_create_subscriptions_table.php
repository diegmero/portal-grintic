<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('restrict');
            $table->decimal('custom_price', 10, 2)->nullable();
            $table->string('billing_cycle'); // monthly, quarterly, yearly
            $table->date('next_billing_date');
            $table->date('started_at');
            $table->date('cancelled_at')->nullable();
            $table->string('status')->default('active'); // active, paused, cancelled
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('client_id');
            $table->index('service_id');
            $table->index(['next_billing_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};