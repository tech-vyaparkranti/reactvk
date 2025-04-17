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
        Schema::create('package_itinerary', function (Blueprint $table) {
            $table->id();
            $table->integer('days')->nullable(false);
            $table->integer('city_id')->index('city_index_id')->nullable(false);
            $table->integer('package_master_id')->index('package_master_index_id')->nullable(false);
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
        Schema::dropIfExists('package_itinary');
    }
};
