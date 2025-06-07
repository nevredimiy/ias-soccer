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
        Schema::table('series_metas', function (Blueprint $table) {

            $table->foreignId('stadium_id')->nullable()->after('event_id')->constrained('stadiums')->nullOnDelete();
            $table->enum('size_field', ['40x20', '60x40'])->nullable()->after('stadium_id');
            $table->dateTime('start_date')->nullable()->after('date');
            $table->dateTime('end_date')->nullable()->after('start_date');
            $table->foreignId('league_id')->nullable()->after('stadium_id')->constrained('leagues')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('series_metas', function (Blueprint $table) {
            $table->dropForeign(['stadium_id']);
            $table->dropColumn('stadium_id');
            $table->dropColumn('size_field');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropForeign(['league_id']);
            $table->dropColumn('league_id');
        });
    }
};
