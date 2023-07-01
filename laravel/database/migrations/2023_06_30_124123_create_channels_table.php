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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('rumble_id', 127)->unique();
            $table->string('url', 255)->unique();
            $table->string('title', 127)->unique();
            $table->date('joining_date');
            $table->text('description')->nullable();
            $table->string('banner', 255)->nullable();
            $table->string('avatar', 255)->nullable();
            $table->unsignedBigInteger('followers_count')->nullable();
            $table->integer('videos_count')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
