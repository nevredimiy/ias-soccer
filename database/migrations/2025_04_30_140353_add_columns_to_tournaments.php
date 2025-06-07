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
        Schema::table('tournaments', function (Blueprint $table) {
            $table->tinyInteger('count_rounds')->default(1)->after('count_teams');
            $table->tinyInteger('count_series')->default(1)->after('count_rounds');
            $table->tinyInteger('count_matches')->default(15)->after('count_series');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('count_rounds');
            $table->dropColumn('count_series');
            $table->dropColumn('count_matches');
        });
    }
};
