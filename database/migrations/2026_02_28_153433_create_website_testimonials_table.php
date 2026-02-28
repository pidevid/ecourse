<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_website_id')->constrained()->cascadeOnDelete();
            $table->string('client_name');
            $table->string('client_role')->nullable();
            $table->text('content');
            $table->string('avatar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_testimonials');
    }
};
