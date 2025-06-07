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
            $table->date('start_date')->nullable()->after('id');
            $table->date('end_date')->nullable()->after('start_date');  
            // добавлям поле цена для серии
            $table->decimal('price_series', 8, 2)->nullable()->after('price');
            // добавлям поле цена для превого внесения при создании команды
            $table->decimal('first_payment', 8, 2)->nullable()->after('price_series');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('price_series');
            $table->dropColumn('first_payment');
        });
    }
};
