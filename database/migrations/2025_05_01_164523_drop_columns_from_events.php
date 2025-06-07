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
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('date');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
            $table->dropColumn('format_scheme');
            $table->dropColumn('size_field');
            $table->dropForeign(['stadium_id']);
            $table->dropColumn('stadium_id');
            $table->dropForeign(['league_id']);
            $table->dropColumn('league_id');
            $table->dropColumn('price_series');
            $table->dropColumn('first_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dateTime('start_date')->nullable()->after('date');
            $table->dateTime('end_date')->nullable()->after('start_date');
            $table->dateTime('date')->nullable()->after('end_date');
            $table->time('start_time')->nullable()->after('date');
            $table->time('end_time')->nullable()->after('start_time');
            $table->string('format_scheme')->nullable()->after('end_time');
            $table->enum('size_field', ['40x20', '60x40'])->nullable()->after('stadium_id');
            $table->foreignId('stadium_id')->nullable()->after('event_id')->constrained('stadiums')->nullOnDelete();
            $table->foreignId('league_id')->nullable()->after('stadium_id')->constrained('leagues')->nullOnDelete();
            $table->decimal('price_series', 8, 2)->default(0)->after('price');
            $table->decimal('first_payment', 8, 2)->default(0)->after('price_series');
        });
    }
};
