<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Process extends Model
{
    use HasFactory;
    protected $fillable = ['order_id','departemen','tanggal','target_harian','output_harian'];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function getStatusAttribute() {
        return $this->output_harian >= $this->target_harian ? 'ok' : 'minus';
    }
}
