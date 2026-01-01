<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('restrict');
            $table->decimal('hours', 5, 2);
            $table->decimal('hourly_rate', 10, 2);
            $table->text('description');
            $table->date('worked_at');
            $table->string('status')->default('pending'); // pending, invoiced
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('client_id');
            $table->index('status');
            $table->index('worked_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_logs');
    }
};