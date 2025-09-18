<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Ticket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            
            $table->integer('identity_type')->default(0);
            $table->string('cnic')->nullable();
            $table->string('student_card')->nullable();
            $table->string('passport')->nullable();

            $table->integer('user_id');
            $table->integer('product_id');
            $table->integer('qty');
            $table->integer('status')->default(0);

            $table->integer('is_used')->default(0);
            $table->dateTime('used_at')->nullable();
            
            $table->json('seat_numbers')->nullable();
            $table->string('reference');
            $table->string('token')->unique();
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
        //
    }
}
