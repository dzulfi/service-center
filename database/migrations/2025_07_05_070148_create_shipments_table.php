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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_item_id')->constrained('service_items')->onDelete('cascade');
            $table->enum('shipment_type', ['To_RMA', 'From_RMA']); // tipe pengiriman: ke rma atau dari rma
            $table->string('resi_number')->nullable(); // nomor resi, bisa kosong diawal saat barang dibuat
            $table->foreignId('responsible_user_id')->nullable()->constrained('users')->onDelete('set null'); // user yang melakukan aksi record ini (terima/kirim)
            $table->string('resi_image_path')->nullable(); // path ke gambar resi
            $table->enum('status', ['Kirim', 'Diterima', 'Kirim kembali', 'Diterima Cabang']); // status pengiriman
            $table->text('notes')->nullable(); // Catatan tambahan untuk pengiriman
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
