<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhyChooseUs extends Model
{
    use HasFactory;

    protected $table = "why_choose_us";

    protected $fillable = [
        'title','description','status',
    ];

    const ID = "id";
    const TITLE = "title";
    const DESCRIPTION = "description";
    const STATUS = "status";
}
