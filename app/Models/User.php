<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Broadcasting\PrivateChannel;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'blocked'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function carts()
    {
        return $this->belongsToMany(Product::class,'carts','id_user','id_product')->as('cart')->withPivot('count','price')->withTimestamps()->orderByPivot('created_at','desc')->orderByPivot('updated_at','desc');
    }

    public function billing()
    {
        return $this->hasMany(Billing::class,'id_user','id');
    }

    public function comments()
    {
        return $this->belongsToMany(Product::class,'comments','id_user','id_product')->withPivot('comment');
    }
}
