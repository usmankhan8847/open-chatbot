<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->text('system_prompt')->nullable();
            $table->string('ai_provider')->default('openai');
            $table->string('ai_model')->default('gpt-3.5-turbo');
            $table->string('api_key')->nullable();
            $table->decimal('temperature', 3, 2)->default(0.7);
            $table->integer('max_tokens')->default(1000);
            $table->text('welcome_message')->nullable();
            $table->json('allowed_domains')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bots');
    }
};
