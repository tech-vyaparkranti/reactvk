<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaDetails extends Migration
{
    public function up()
    {
        Schema::table('package_master', function (Blueprint $table) {
            $table->string('meta_title', 500)->nullable(true)->default(null)->after('description');
            $table->text('meta_keyword')->nullable(true)->after('meta_title');
            $table->text('meta_description')->nullable(true)->after('meta_keyword');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_master', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_keyword', 'meta_description']);
        });
    }
}
