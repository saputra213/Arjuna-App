<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('cabangs', function (Blueprint $table) {
        $table->id();
        $table->string('kode_cabang')->unique();
        $table->string('nama_cabang');
        $table->string('alamat')->nullable();
        $table->string('telepon')->nullable();
        $table->timestamps();
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabangs');
    }
};
