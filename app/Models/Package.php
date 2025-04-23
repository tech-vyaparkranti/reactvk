<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = "packages";
    protected $fillable = [
        'category','package_class','price' ,'package_details','status','position','title'
    ];

    const ID = "id";
}
