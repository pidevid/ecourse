<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_website_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('url')->nullable();
            $table->string('tech_stack')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_portfolios');
    }
};
