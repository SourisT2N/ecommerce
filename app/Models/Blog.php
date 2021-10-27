<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsResponseCache;

class Blog extends Model
{
    use HasFactory, ClearsResponseCache;
    protected $table = 'blogs';
    protected $fillable = [
        'name',
        'name_not_utf8',
        'image_display',
        'body',
        'summary'
    ];
}
