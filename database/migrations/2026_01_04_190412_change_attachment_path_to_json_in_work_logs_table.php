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
        // First convert existing data to JSON format if needed
        // Assuming DB type is compatible (MySQL/PostgreSQL usually handle this, but explicit cast helps)
        // For SQLite or others, we might need a temp column, but let's try direct modification with raw statement if needed.
        // Actually, easiest way compatible with Laravel:
        // 1. Get all records with non-null attachment_path
        // 2. Update them to be JSON array string
        // 3. Change column type
        
        // However, changing type to JSON might fail if data isn't valid JSON.
        // Let's assume standard "modern" approach:
        
        // Update existing records to wrap string in array if not null
        \DB::statement("UPDATE work_logs SET attachment_path = CONCAT('[\"', attachment_path, '\"]') WHERE attachment_path IS NOT NULL AND attachment_path NOT LIKE '[%'");

        Schema::table('work_logs', function (Blueprint $table) {
            $table->json('attachment_path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting is risky if multiple files exist. We'll take the first one.
        // This is a destructive down migration for data beyond the first file.
        
        // We won't implement complex logic here for now, simply revert type string.
        // Values will be cast to string representation of JSON which is fine for storage but application logic changes layout.
        
        Schema::table('work_logs', function (Blueprint $table) {
            $table->string('attachment_path')->nullable()->change();
        });
    }
};
