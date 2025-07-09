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
        Schema::table('service_items', function (Blueprint $table) {
            $table->enum('location_status', [
                'At_BranchOffice',
                'In_Transit_To_RMA',
                'At_RMA',
                'In_Transit_From_RMA',
                'Ready_For_Pickup', // opsi jika barang sudah diterima di cabang dan siap diambil customer
            ])->default('At_BranchOffice')->after('merk'); // default barang saat dibuat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_items', function (Blueprint $table) {
             $table->dropColumn('location_status');
        });
    }
};
