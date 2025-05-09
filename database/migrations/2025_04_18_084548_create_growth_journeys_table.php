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
        Schema::create('growth_journeys', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('icon');
            $table->tinyInteger('status')->default('1');
            $table->string('experience_level')->nullable();
            $table->longText('short_description');
            $table->string('position');
            $table->longText('skills');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('growth_journeys');
    }
};
