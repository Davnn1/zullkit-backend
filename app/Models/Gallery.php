<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'products_id', 'url', 'is_featured'
    ];


    public function getUrlAttribute($url)
    {
        return Storage::url($url);
    }
}
