<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $fillable = ['kode_cabang', 'nama_cabang', 'alamat', 'telepon'];

    public function gaji()
    {
        return $this->hasMany(Gaji::class, 'cabang_id');
    }
}
?>