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
        Schema::create('series_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_meta_id')->constrained('series_metas')->onDelete('cascade');
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('player_id')->nullable()->constrained('players')->onDelete('cascade');
            $table->unsignedSmallInteger('wins')->default(0);
            $table->unsignedSmallInteger('draw')->default(0);
            $table->unsignedSmallInteger('defeat')->default(0);
            $table->unsignedSmallInteger('goals_scored')->default(0);
            $table->unsignedSmallInteger('goals_conceded')->default(0); // был missed_goals
            $table->integer('goal_difference')->default(0); // со знаком
            $table->unsignedSmallInteger('points')->default(0);
            $table->unsignedTinyInteger('scores')->default(0);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series_results');
    }
};
