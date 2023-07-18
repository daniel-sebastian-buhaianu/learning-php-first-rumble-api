<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('url', 255)->unique();
            $table->string('title', 128)->unique();
            $table->date('joining_date');
            $table->unsignedBigInteger('followers_count')->default(0);
            $table->integer('videos_count')->default(0);
            $table->string('banner', 255)->nullable();
            $table->string('avatar', 255)->nullable();
            $table->text('description')->nullable();
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
