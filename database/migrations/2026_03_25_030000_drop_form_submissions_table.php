<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('form_submissions');
    }

    public function down(): void
    {
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('form_id');
            $table->string('form_path');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->json('data');
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }
};
