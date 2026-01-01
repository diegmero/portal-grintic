<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('file_path', 500);
            $table->date('month_year');
            $table->string('report_type'); // monthly_summary, project_report, custom
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('client_id');
            $table->index('month_year');
            $table->index('report_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};