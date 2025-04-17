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
        Schema::create('contact_us', function (Blueprint $table) {
            $table->id();
            // $table->string("first_name",50)->nullable();
            $table->string("name",50)->nullable();
            $table->string("address",50)->nullable();
            // $table->string("email",255)->nullable()->index("contact_us_email");
            // $table->string("country_code",10)->nullable();
            $table->string("phone_number",100)->nullable()->index("contact_us_phone_number");
            $table->text("message")->nullable(false);
            $table->string("ip_address",50)->nullable();
            $table->string("user_agent",255)->nullable();
            $table->tinyInteger("status")->nullable(false)->default("1");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_us');
    }
};
