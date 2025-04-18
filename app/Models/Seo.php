<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use HasFactory;

    protected $table = "seos";

    protected $fillable = [
        'website_url','phone' ,'ip_address'
    ];
}
