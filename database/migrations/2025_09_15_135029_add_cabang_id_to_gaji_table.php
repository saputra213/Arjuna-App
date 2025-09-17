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
    Schema::table('gaji', function (Blueprint $table) {
        $table->unsignedBigInteger('cabang_id')->nullable()->after('user_id');
        $table->foreign('cabang_id')->references('id')->on('cabangs')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gaji', function (Blueprint $table) {
            //
        });
    }
};
