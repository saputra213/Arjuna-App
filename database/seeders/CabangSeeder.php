<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
    \App\Models\Cabang::insert([
        ['kode_cabang' => 'CBG1', 'nama_cabang' => 'Cabang Sukoharjo', 'alamat' => 'Jl. Sudirman', 'telepon' => '021123456'],
        ['kode_cabang' => 'CBG2', 'nama_cabang' => 'Cabang Yogyakarta', 'alamat' => 'Jl. Asia Afrika', 'telepon' => '022987654'],
        ['kode_cabang' => 'CBG3', 'nama_cabang' => 'Cabang Semarang', 'alamat' => 'Jl. Lawang Sewu', 'telepon' => '08937652354'],
    ]);
    }

}
