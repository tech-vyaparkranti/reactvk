<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = "galleries";
    protected $fillable = [
        'image','filter_category','sorting','status' 
    ];

    const ID = "id";
    const IMAGE = "image";
    const CATEGORY = "filter_category";
    const POSITION = "sorting";
    const STATUS = "status";
}
