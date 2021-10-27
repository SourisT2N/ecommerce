<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsResponseCache;

class Billing extends Model
{
    use HasFactory, ClearsResponseCache;
    protected $table = 'billings';
    protected $fillable = [
        'name','id_user','id_payment',
        'phone','address','district','province','ward',
        'total','status_payment','id_status','code_billing'
    ];

    public function payments()
    {
        return $this->belongsTo(Payment::class,'id_payment','id');
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class,'id_status','id');
    }

    public function users()
    {
        return $this->belongsTo(User::class,'id_user','id');
    }

    public function details()
    {
        return $this->belongsToMany(Product::class,'billing_details','id_billing','id_product')->withPivot('count','price')->withTimestamps();
    }
}
