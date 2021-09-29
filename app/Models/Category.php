<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\ClearsResponseCache;

class Category extends Model
{
    use HasFactory, ClearsResponseCache;
    protected $table = 'categories';
    protected $fillable = [
        'name','code_category','name_not_utf8'
    ];

    public function products()
    {
        return $this->hasMany(Product::class,'id_category','id');
    }
}
