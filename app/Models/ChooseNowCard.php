<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChooseNowCard extends Model
{
    use HasFactory;

    protected $table = "choose_now_cards";

    protected $fillable = [
        "icon",'title','description' ,'status'
    ];

    const ID = "id";
}
