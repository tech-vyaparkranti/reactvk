<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory;

    protected $table = "vacancies";

    protected $fillable = [
        'title' ,'department' ,'location' ,'job_type' ,'description','requirement','benefits','status'
    ];

    const ID = "id";
    const TITLE = "title";
    const DEPARTMENT = "department";
    const LOCATION = "location";
    const JOB_TYPE = "job_type";
    const DESCRIPTION = "description";
    const REQUIREMENT = "requirement";
    const BENEFITS = "benefits";
    const STATUS = "status";

}
