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
        Schema::create('stadia', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('photo')->nullable();
            $table->integer('fields_40x20')->default(0);
            $table->integer('fields_60x40')->default(0);
            $table->integer('parking_spots')->default(0);
            $table->boolean('has_shower')->default(false);
            $table->boolean('has_speaker_system')->default(false);
            $table->boolean('has_wardrobe')->default(false);
            $table->boolean('has_toilet')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stadia');
    }
};
