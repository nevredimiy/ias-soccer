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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['team', 'solo'])->default('team');
            $table->enum('subtype', ['one-day', 'regular'])->default('regular');
            $table->tinyInteger('count_teams')->default(3);  
            $table->tinyInteger('count_rounds')->default(1);
            $table->tinyInteger('count_series')->default(1);
            $table->tinyInteger('count_matches')->default(15); 
            $table->tinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
