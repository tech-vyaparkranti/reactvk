<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Blog;

class BlogReview extends Model
{
    use HasFactory;

    protected $table = "blog_reviews";

    protected $fillable = [
        'first_name','last_name' ,'phone','comments','review','blog_id','status'
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class,'blog_id');
    }
}
