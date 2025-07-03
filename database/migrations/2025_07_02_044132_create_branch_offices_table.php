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
        Schema::create('branch_offices', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // nama kantor cabang (misal: Cabang Jakarta)
            $table->text('address')->nullable();
            $table->string('sub_district')->nullable(); // kelurahan
            $table->string('district')->nullable();     // kecamatan
            $table->string('city')->nullable();         // kota
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_offices');
    }
};
