<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('image',500)->nullable(false);
            $table->string('title',500)->nullable(false);
            $table->string('slug')->unique();
            $table->longtext('content')->nullable(false)->default(null);
            $table->string('blog_date')->nullable(false)->default(null);
            $table->string('facebook_link')->nullable(true)->default(null);
            $table->string('instagram_link')->nullable(true)->default(null);
            $table->string('twitter_link')->nullable(true)->default(null);
            $table->string('youtube_link')->nullable(true)->default(null);
            $table->string('blog_category',500)->nullable(true)->default(null);
            $table->enum('blog_status',["live","disabled"])->nullable(false)->default("disabled");
            $table->integer('blog_sorting')->nullable(false)->default("1")->index("blogs_index");
            $table->tinyInteger('status')->default('1')->nullable(false);
            $table->bigInteger("created_by")->nullable(true);
            $table->bigInteger("updated_by")->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
