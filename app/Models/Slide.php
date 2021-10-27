<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsResponseCache;

class Slide extends Model
{
    use HasFactory, ClearsResponseCache;
    protected $table = 'slides';
    protected $fillable = [
        'subject','content','url','image_display'
    ];
}
