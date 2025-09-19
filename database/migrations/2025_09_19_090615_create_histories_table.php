<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal_selesai');
            $table->integer('total_output');
            $table->decimal('total_penghasilan', 12,2);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('histories');
    }
};

