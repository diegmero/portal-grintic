<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('country_code', 2)->nullable()->after('company_name');
            $table->string('tax_id_type', 20)->nullable()->after('country_code');
            // Renombrar tax_id a tax_id_number para mayor claridad
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['country_code', 'tax_id_type']);
        });
    }
};
