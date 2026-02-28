<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_website_id')->constrained()->cascadeOnDelete();
            $table->string('full_name')->nullable();
            $table->string('role_title')->nullable();
            $table->string('short_bio')->nullable();
            $table->text('about_me')->nullable();
            $table->string('avatar')->nullable();
            $table->string('cv_file')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_profiles');
    }
};
