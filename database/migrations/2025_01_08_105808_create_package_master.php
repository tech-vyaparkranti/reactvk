<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_master', function (Blueprint $table) {
            $table->id();
            $table->string("package_name",255)->unique("unique_package_name")->nullable(false);
            $table->string('slug')->unique()->nullable();
            $table->text("package_image")->nullable(false);
            $table->string("package_country",50)->nullable(false);
            $table->string('destination_slug', 255)->nullable(false);
            $table->longText('description')->nullable();
            $table->tinyInteger("status")->nullable(false)->default("1");
            $table->bigInteger("created_by")->nullable(true);
            $table->bigInteger("updated_by")->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('package_master');
    }
}
