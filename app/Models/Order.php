<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['brand','jumlah_order','price_per_pcs'];

    public function processes() {
        return $this->hasMany(Process::class);
    }

    public function history() {
        return $this->hasOne(History::class);
    }
}
