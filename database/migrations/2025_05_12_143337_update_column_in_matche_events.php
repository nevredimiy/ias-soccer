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
        Schema::table('matche_events', function (Blueprint $table) {
           

            // Создадим новую с расширенными типами
            $table->enum('type', [
                'goal',
                'assist',
                'yellow_card',
                'red_card',
                'own_goal',
                'autogoal',      // добавляем новый тип
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matche_events', function (Blueprint $table) {
            $table->enum('type', [
                'goal',
                'assist',
                'yellow_card',
                'red_card',
                'own_goal'
            ])->change();
        });
    }
};
