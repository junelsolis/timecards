<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Timecards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('timecards', function(Blueprint $table) {
        $table->string('grade', 1)->default('s')->change();

        $table->string('sunTimeIn1', 5)->nullable()->change();
        $table->string('sunTimeOut1', 5)->nullable()->change();
        $table->string('sunTimeIn2', 5)->nullable()->change();
        $table->string('sunTimeOut2', 5)->nullable()->change();

        $table->string('monTimeIn1', 5)->nullable()->change();
        $table->string('monTimeOut1', 5)->nullable()->change();
        $table->string('monTimeIn2', 5)->nullable()->change();
        $table->string('monTimeOut2', 5)->nullable()->change();


        $table->string('tueTimeIn1', 5)->nullable()->change();
        $table->string('tueTimeOut1', 5)->nullable()->change();
        $table->string('tueTimeIn2', 5)->nullable()->change();
        $table->string('tueTimeOut2', 5)->nullable()->change();


        $table->string('wedTimeIn1', 5)->nullable()->change();
        $table->string('wedTimeOut1', 5)->nullable()->change();
        $table->string('wedTimeIn2', 5)->nullable()->change();
        $table->string('wedTimeOut2', 5)->nullable()->change();


        $table->string('thuTimeIn1', 5)->nullable()->change();
        $table->string('thuTimeOut1', 5)->nullable()->change();
        $table->string('thuTimeIn2', 5)->nullable()->change();
        $table->string('thuTimeOut2', 5)->nullable()->change();


        $table->string('friTimeIn1', 5)->nullable()->change();
        $table->string('friTimeOut1', 5)->nullable()->change();
        $table->string('friTimeIn2', 5)->nullable()->change();
        $table->string('friTimeOut2', 5)->nullable()->change();


        $table->string('satTimeIn1', 5)->nullable()->change();
        $table->string('satTimeOut1', 5)->nullable()->change();
        $table->string('satTimeIn2', 5)->nullable()->change();
        $table->string('satTimeOut2', 5)->nullable()->change();

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
