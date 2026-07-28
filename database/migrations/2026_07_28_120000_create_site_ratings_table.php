<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteRatingsTable extends Migration
{
    public function up()
    {
        Schema::create('site_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vote_id')->nullable()->index();
            $table->unsignedInteger('musician_id')->nullable()->index();
            $table->string('display_name', 120);
            $table->string('display_as', 20)->default('voter'); // voter|contestant
            $table->string('country', 8)->nullable();
            $table->unsignedTinyInteger('stars'); // 1–5
            $table->text('comment')->nullable();
            $table->boolean('is_visible')->default(false);
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_ratings');
    }
}
