<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->enum('departemen', ['cutting','produksi','finishing']);
            $table->date('tanggal');
            $table->integer('target_harian');
            $table->integer('output_harian')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('processes');
    }
};

