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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name')->unique();
            $table->string('logo')->nullable();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->enum('status', ['awaiting_payment', 'paid'])->default('awaiting_payment');
            $table->enum('player_request_status', ['urgent', 'needed', 'closed'])->default('closed');
            $table->integer('max_players')->default(6);  
            $table->integer('application_lifetime_days')->default(6);
            $table->integer('application_lifetime_hours')->default(0);
            $table->integer('application_lifetime_minutes')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
