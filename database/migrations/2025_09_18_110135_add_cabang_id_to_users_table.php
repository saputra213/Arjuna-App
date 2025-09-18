<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('cabang_id')->nullable()->after('id');
        $table->foreign('cabang_id')->references('id')->on('cabangs')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['cabang_id']);
        $table->dropColumn('cabang_id');
    });
}

};
