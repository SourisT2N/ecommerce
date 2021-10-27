<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsResponseCache;

class Supplier extends Model
{
    use HasFactory, ClearsResponseCache;
    protected $table = 'suppliers';
    protected $fillable = [
        'name','code_supplier'
    ];
    
    public function products()
    {
        return $this->hasMany(Product::class,'id_supplier','id');
    }
}
