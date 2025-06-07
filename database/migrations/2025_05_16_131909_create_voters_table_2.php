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

        Schema::create('voters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_meta_id')->constrained('series_metas')->onDelete('cascade');
            $table->foreignId('voted_player')->constrained('players')->onDelete('cascade');
            $table->foreignId('best_player1')->constrained('players')->onDelete('cascade');
            $table->foreignId('best_player2')->constrained('players')->onDelete('cascade');
            $table->foreignId('worst_player1')->constrained('players')->onDelete('cascade');
            $table->foreignId('worst_player2')->constrained('players')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voters');
    }
};
