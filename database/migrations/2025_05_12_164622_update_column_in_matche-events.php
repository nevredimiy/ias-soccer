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
            $table->unsignedTinyInteger('minute')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('matche_events', function (Blueprint $table) {
            $table->unsignedTinyInteger('minute')->nullable(false)->change();
        });
    }
};
