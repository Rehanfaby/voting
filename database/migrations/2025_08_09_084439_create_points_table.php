<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('judge_id');
            $table->integer('candidate_id');

            // Score fields
            $table->integer('depth')->comment('1. Profondeur / 20')->default(0);
            $table->integer('diction')->comment('2. Diction / 10')->default(0);
            $table->integer('accuracy')->comment('3. Justesse / 10')->default(0);
            $table->integer('interpretation')->comment('4. Interprétation / 10')->default(0);
            $table->integer('technique')->comment('5. Technique vocale / 10')->default(0);
            $table->integer('stage_presence')->comment('6. Présentation scénique / 10')->default(0);
            $table->integer('song_choice')->comment('7. Choix de la chanson / 10')->default(0);
            $table->integer('overall_presentation')->comment('8. Présentation générale / 10')->default(0);
            $table->integer('adaptability')->comment('9. Adaptabilité / 5')->default(0);
            $table->integer('audience_interaction')->comment('10. Interaction du public / 5')->default(0);

            $table->integer('total')->default(0);
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
        Schema::dropIfExists('points');
    }
}
