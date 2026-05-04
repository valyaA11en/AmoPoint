<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();

            $table->uuid('visitor_id')->index();

            $table->string('ip_address', 45)->nullable()->index();
            $table->string('city')->nullable()->index();

            $table->string('device_type', 30)->index();
            $table->text('user_agent')->nullable();

            $table->string('page_url', 2048);
            $table->string('referrer', 2048)->nullable();

            $table->string('language', 20)->nullable();
            $table->string('timezone')->nullable();
            $table->string('screen')->nullable();

            $table->timestamp('visited_at')->index();

            $table->timestamps();

            $table->index(['visitor_id', 'visited_at']);
            $table->index(['city', 'visited_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};