<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsResponseCache;

class Country extends Model
{
    use HasFactory, ClearsResponseCache;
    protected $table = 'countries';
    protected $fillable = [
        'name','code_country'
    ];

    public function products()
    {
        return $this->hasMany(Product::class,'id_country','id');
    }
}
