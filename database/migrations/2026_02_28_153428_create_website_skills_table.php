<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_website_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('level', ['beginner', 'intermediate', 'expert'])->default('intermediate');
            $table->tinyInteger('percentage')->default(50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_skills');
    }
};
