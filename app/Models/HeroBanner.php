<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroBanner extends Model
{
    use HasFactory;

    protected $table = "hero_banners";

    protected $fillable = [
        'image','title','sub_title','status'
    ];

    const IMAGE = "image";
    const ID = "id";
    const TITLE = "title";
    const SUB_TITLE = "sub_title";
    const STATUS = "status";
}
