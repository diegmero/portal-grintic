<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('total_budget', 10, 2);
            $table->string('status')->default('planning'); // planning, development, qa, done, cancelled
            $table->date('started_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('client_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};