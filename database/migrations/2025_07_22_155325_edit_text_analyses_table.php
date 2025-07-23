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
            $table->float('plagiarism_percentage')->default(0);
            $table->longText('excerpted_text')->nullable();
            $table->longText('similarities')->nullable();
            $table->dropColumn('analysis_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('text_analyses', function (Blueprint $table) {
            $table->dropColumn('plagiarism_percentage');
            $table->dropColumn('excerpted_text');
            $table->dropColumn('similarities');
            $table->longText('analysis_result')->nullable();
        });
    }
};
