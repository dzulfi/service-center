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
        Schema::table('stock_spareparts', function (Blueprint $table) {
            DB::statement("ALTER TABLE stock_spareparts CHANGE service_process_id service_item_id BIGINT UNSIGNED");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_spareparts', function (Blueprint $table) {
            DB::statement("ALTER TABLE stock_spareparts CHANGE service_item_id service_process_id BIGINT UNSIGNED");
        });
    }
};
