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
        Schema::create('series_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_meta_id')->constrained('series_metas')->onDelete('cascade');
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();
            
            // Уникальность пары series_meta + team
            $table->unique(['series_meta_id', 'team_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series_teams');
    }
};
