<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            // $table->string('email')->index('email_index');
            $table->string('phone_number', 20)->index('phone_number_index');
            // $table->string('package_name', 255)->nullable(false); // Made services optional
            $table->text('message')->nullable(); // Made message optional
            // $table->timestamp('travel_date')->nullable(); // Added travel date field
            // $table->integer('traveller_count')->nullable(); // Added traveller count field
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
        Schema::dropIfExists('enquiries');
    }
}