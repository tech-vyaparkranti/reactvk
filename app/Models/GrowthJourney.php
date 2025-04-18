<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowthJourney extends Model
{
    use HasFactory;

    protected $table = "growth_journeys";
    protected $fillable = [
        'title','icon','status','experience_level','short_description' ,'position','skills'
    ];

    const ID = "id";
    const TITLE = "title";
    const ICON = "icon";
    const STATUS = "status";
    const EX_LEVEL = "experience_level";
    const SHORT_DESCRIPTION = "short_description";
    const POSITION = "position";
    const SKILL = "skills";
}
