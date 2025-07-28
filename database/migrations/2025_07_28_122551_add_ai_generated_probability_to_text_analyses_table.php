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
        Schema::table('text_analyses', function (Blueprint $table) {
            $table->float('ai_generated_probability')->nullable()->after('plagiarism_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('text_analyses', function (Blueprint $table) {
            $table->dropColumn('ai_generated_probability');
        });
    }
};
