<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::create('gaji', function (Blueprint $table) {
        $table->id();

        // karena users.id adalah VARCHAR/ULID
        $table->string('user_id');  

        $table->string('periode'); 
        $table->integer('total_hari');
        $table->decimal('gaji_pokok', 12, 2);
        $table->decimal('tunjangan', 12, 2)->default(0);
        $table->decimal('potongan', 12, 2)->default(0);
        $table->decimal('total_gaji', 12, 2);

        $table->timestamps();

        // kalau mau tetap ada relasi, pakai index biasa
        // tapi foreign key tidak bisa langsung, jadi:
        // $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        // (comment dulu, karena beda tipe)
    });
}


    public function down(): void {
        Schema::dropIfExists('gaji');
    }
};
