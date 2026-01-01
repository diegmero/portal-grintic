<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('tax_id', 50)->nullable()->unique();
            $table->text('internal_notes')->nullable();
            $table->string('status')->default('active'); // active, inactive, blacklisted
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('status');
            $table->index('company_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};