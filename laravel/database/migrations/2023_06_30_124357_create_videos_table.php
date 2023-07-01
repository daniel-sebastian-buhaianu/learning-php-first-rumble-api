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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('channel_id');
            $table->text('html');
            $table->string('url', 255)->unique();
            $table->string('title', 255)->unique();
            $table->string('thumbnail', 255);
            $table->string('duration', 15);
            $table->dateTime('uploaded_at');
            $table->unsignedBigInteger('likes_count')->nullable();
            $table->unsignedBigInteger('dislikes_count')->nullable();
            $table->unsignedBigInteger('views_count')->nullable();
            $table->unsignedBigInteger('comments_count')->nullable();
            $table->timestamps();

            $table->foreign('channel_id')
                ->references('id')
                ->on('channels')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
