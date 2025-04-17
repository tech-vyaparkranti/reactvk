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
        Schema::create('news_letter_emails', function (Blueprint $table) {
            $table->id();
            $table->string("email_id",255)->unique("nle_email_id_unique")->nullable(false);
            $table->string("ip_address",255);
            $table->string("user_agent",255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('news_letter_emails');
    }
};
