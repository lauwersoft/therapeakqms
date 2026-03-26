<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 30)->default('page_view')->index();
            $table->string('path', 500);
            $table->string('doc_id', 20)->nullable()->index();
            $table->string('doc_title', 255)->nullable();
            $table->unsignedSmallInteger('time_spent')->default(0);
            $table->string('device', 20)->nullable();
            $table->unsignedSmallInteger('viewport_w')->nullable();
            $table->unsignedSmallInteger('viewport_h')->nullable();
            $table->string('browser', 50)->nullable();
            $table->string('os', 50)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->unsignedTinyInteger('scroll_depth')->nullable();
            $table->string('page_title', 255)->nullable();
            $table->text('detail')->nullable();
            $table->string('ip', 45)->nullable();
            $table->char('country_code', 2)->nullable()->index();
            $table->unsignedInteger('asn_number')->nullable();
            $table->string('asn_org', 100)->nullable();
            $table->string('session_uid', 36)->nullable()->index();
            $table->string('browser_uid', 36)->nullable()->index();
            $table->string('timezone', 50)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at']);
            $table->index('ip');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_activities');
    }
};
