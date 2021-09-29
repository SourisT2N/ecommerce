<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\ClearsResponseCache;

class Payment extends Model
{
    use HasFactory, ClearsResponseCache;
    protected $table = 'payments';
    
    public function billings()
    {
        return $this->hasMany(Product::class,'id_payment','id');
    }
}
