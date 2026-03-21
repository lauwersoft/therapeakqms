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
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('form_id'); // e.g. FM-001
            $table->string('form_path'); // path to the form template
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title'); // submission title/reference
            $table->json('data'); // the filled form data
            $table->string('status')->default('draft'); // draft, submitted, approved
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};
