<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_websites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('theme', ['minimal', 'creative', 'professional'])->default('minimal');
            $table->string('accent_color')->default('#14b8a6');
            $table->enum('font_family', ['sans', 'serif', 'mono'])->default('sans');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_websites');
    }
};
