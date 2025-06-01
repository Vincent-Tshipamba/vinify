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
            $table->string('status')->default('pending');
            $table->text('error_message')->nullable();
            $table->text('analysis_result')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('text_analyses', function (Blueprint $table) {
            //
        });
    }
};
