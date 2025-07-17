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
            // tambahkan kolom relasi item_types
            $table->foreignId('item_type_id')->nullable()->constrained('item_types')->cascadeOnDelete();

            // Hapus kolom lama
            $table->dropColumn(['type', 'merk']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_items', function (Blueprint $table) {
            // Rollback: tambahkan kembali field lama
            $table->string('type')->nullable();
            $table->string('merk')->nullable();

            // hapus relasi ke item_type
            $table->dropForeign(['item_type_id']);
            $table->dropColumn('item_type_id');
        });
    }
};
