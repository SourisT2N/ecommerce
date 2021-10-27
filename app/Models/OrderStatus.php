<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsResponseCache;

class OrderStatus extends Model
{
    use HasFactory, ClearsResponseCache;
    protected $table = 'order_status';
    protected $fillable = [
        'name'
    ];

    public function billings()
    {
        return $this->hasMany(Billing::class,'id_status','id');
    }
}
