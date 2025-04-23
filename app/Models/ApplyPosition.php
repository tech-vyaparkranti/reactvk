<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplyPosition extends Model
{
    use HasFactory;

    protected $table = "apply_positions";

    protected $fillable = [
        'name','email','phone','department','position_analytics','resume','cover_letter'
    ];

    const ID = "id";
}
