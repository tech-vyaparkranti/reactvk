<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    use HasFactory;

    protected $table = "technologies";

    protected $fillable = [
        'image','tech_name' ,'status' ,'sorting','description','category'
    ];

    const ID = "id";
    const IMAGE = "image";
    const TECH_NAME = "tech_name";
    const STATUS = "status";
    const POSITION = "sorting";
    const DESCRIPTION = "description";
    const CATEGORY = "category";
}
