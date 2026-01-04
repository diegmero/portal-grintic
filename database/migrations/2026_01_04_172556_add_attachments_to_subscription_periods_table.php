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
        Schema::table('subscription_periods', function (Blueprint $table) {
            $table->json('attachments')->nullable()->after('internal_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_periods', function (Blueprint $table) {
            $table->dropColumn('attachments');
        });
    }
};
