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
        Schema::table('training_data', function (Blueprint $table) {
            $table->longText('content')->nullable()->after('file_path');
            $table->string('original_url')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_data', function (Blueprint $table) {
            $table->dropColumn(['content', 'original_url']);
        });
    }
};
