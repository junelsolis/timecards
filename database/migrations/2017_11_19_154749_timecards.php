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

        $table->string('sunTimeIn1', 8)->nullable()->change();
        $table->string('sunTimeOut1', 8)->nullable()->change();
        $table->string('sunTimeIn2', 8)->nullable()->change();
        $table->string('sunTimeOut2', 8)->nullable()->change();

        $table->string('monTimeIn1', 8)->nullable()->change();
        $table->string('monTimeOut1', 8)->nullable()->change();
        $table->string('monTimeIn2', 8)->nullable()->change();
        $table->string('monTimeOut2', 8)->nullable()->change();


        $table->string('tueTimeIn1', 8)->nullable()->change();
        $table->string('tueTimeOut1', 8)->nullable()->change();
        $table->string('tueTimeIn2', 8)->nullable()->change();
        $table->string('tueTimeOut2', 8)->nullable()->change();


        $table->string('wedTimeIn1', 8)->nullable()->change();
        $table->string('wedTimeOut1', 8)->nullable()->change();
        $table->string('wedTimeIn2', 8)->nullable()->change();
        $table->string('wedTimeOut2', 8)->nullable()->change();


        $table->string('thuTimeIn1', 8)->nullable()->change();
        $table->string('thuTimeOut1', 8)->nullable()->change();
        $table->string('thuTimeIn2', 8)->nullable()->change();
        $table->string('thuTimeOut2', 8)->nullable()->change();


        $table->string('friTimeIn1', 8)->nullable()->change();
        $table->string('friTimeOut1', 8)->nullable()->change();
        $table->string('friTimeIn2', 8)->nullable()->change();
        $table->string('friTimeOut2', 8)->nullable()->change();


        $table->string('satTimeIn1', 8)->nullable()->change();
        $table->string('satTimeOut1', 8)->nullable()->change();
        $table->string('satTimeIn2', 8)->nullable()->change();
        $table->string('satTimeOut2', 8)->nullable()->change();

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
