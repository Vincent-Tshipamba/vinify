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
            $table->longText('content')->change();
            $table->longText('highlighted_text')->nullable();
            $table->longText('analysis_result')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('text_analyses', function (Blueprint $table) {
            $table->text('content')->change();
            $table->dropColumn('highlighted_text');
            $table->text('analysis_result')->change();
        });
    }
};
