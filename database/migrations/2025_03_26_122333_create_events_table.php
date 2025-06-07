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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('format', ['5x5x5', '4x4x4', '9x9x9'])->default('5x5x5');
            $table->unsignedTinyInteger('format_scheme')->nullable();
            $table->enum('size_field', ['40x20', '60x40'])->default('40x20');
            $table->foreignId('stadium_id')->nullable()->constrained('stadiums')->nullOnDelete();
            $table->foreignId('tournament_id')->constrained('tournaments')->onDelete('cascade');
            $table->foreignId('league_id')->nullable()->constrained('leagues')->onDelete('set null');
            $table->decimal('price', 8, 2)->default(0);
            $table->string('access_code', 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
