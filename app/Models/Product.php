<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsResponseCache;

class Product extends Model
{
    use HasFactory, ClearsResponseCache;
    protected $table = 'products';
    protected $fillable = [
        'name','name_not_utf8','description',
        'price_old','price_new','system','display',
        'processor','graphics','memory','hard_drive',
        'wireless','battery','image_display','id_country','id_category','id_supplier'
    ];

    protected $hidden = [
        'created_at','updated_at','description',
        'price_old','price_new','system','display',
        'processor','graphics','memory','hard_drive',
        'wireless','battery','id_country','id_category','id_supplier'
    ];

    public function images()
    {
        return $this->hasMany(ImageProduct::class,'id_product','id');
    }

    public function countries()
    {
        return $this->belongsTo(Country::class,'id_country','id');
    }

    public function categories()
    {
        return $this->belongsTo(Category::class,'id_category','id');
    }

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class,'id_supplier','id');
    }

    public function orders()
    {
        return $this->belongsToMany(Product::class,'billing_details','id_product','id_billing')->withPivot('count','price');
    }

    public function comments()
    {
        return $this->belongsToMany(User::class,'comments','id_product','id_user')->withPivot('comment')->withTimestamps();
    }
}
