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
        
        Schema::create('our_services', function (Blueprint $table) {
            $table->id();
            $table->string("service_name",255)->nullable(false)->index("index_service_name");
            $table->text("service_details")->nullable(true);
            $table->text("service_image")->nullable(false);
            $table->integer("position")->default(10)->nullable(true)->index("index_position");
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
        Schema::dropIfExists('our_services');
    }
};
