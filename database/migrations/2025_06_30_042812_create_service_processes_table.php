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
        Schema::create('service_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_item_id')->constrained()->onDelete('cascade');
            $table->text('damage_analysis_detail')->nullable();
            $table->text('solution')->nullable();
            $table->string('process_status');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_processes');
    }
};
