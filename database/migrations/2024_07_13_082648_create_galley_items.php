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
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string("local_image",255)->nullable();
            $table->string("image_link",255)->nullable();
            $table->string("alternate_text",255)->default("image");
            $table->string("title",255)->nullable();
            $table->integer("position")->default(10);
            $table->enum('view_status',['hidden','visible'])->nullable(false)->default("visible");
            $table->tinyInteger("status")->nullable(false)->default("1");
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
        Schema::dropIfExists('galley_items');
    }
};
