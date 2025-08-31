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
        Schema::create('elo_history', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->uuid('player_id')->nullable()->unique();
            $table->string('nickname')->unique();
            $table->string('game_id')->nullable();
            $table->integer('elo')->nullable();
            $table->dateTime('recorded_at')->nullable()->useCurrent();

            $table->foreign('player_id')->references('faceit_id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elo_history');
    }
};
