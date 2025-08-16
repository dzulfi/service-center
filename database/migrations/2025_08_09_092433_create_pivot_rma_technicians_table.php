<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pivot_rma_technicians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rma_technician_id')->nullable()->constrained('rma_technicians')->onDelete('cascade');
            $table->foreignId('service_item_id')->nullable()->constrained('service_items')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot_rma_technicians');
    }
};
