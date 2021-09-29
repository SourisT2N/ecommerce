<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsResponseCache;

class ImageProduct extends Model
{
    use HasFactory, ClearsResponseCache;
    protected $table = 'images_product';
    protected $fillable = [
        'image_display','id_product'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class,'id_product','id');
    }
}
