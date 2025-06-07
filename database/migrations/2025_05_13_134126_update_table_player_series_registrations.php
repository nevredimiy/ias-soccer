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
        Schema::table('player_series_registrations', function (Blueprint $table) {
            $table->foreignId('series_meta_id')->after('id')->constrained('series_metas')->onDelete('cascade');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
        $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
        $table->unsignedTinyInteger('player_number');
        $table->unsignedTinyInteger('series');
        $table->unsignedTinyInteger('round')->nullable();
    }
};
