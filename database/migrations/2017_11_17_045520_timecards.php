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
      Schema::create('timecards', function(Blueprint $table) {
        $table->increments('id');
        $table->integer('worker_id');
        $table->integer('dept_id');
        $table->string('startDate', 10);
        $table->string('endDate', 10);
        $table->integer('contract')->nullable();
        $table->float('hours', 5,2)->default(0);
        $table->string('grade', 1);
        $table->boolean('signed')->default(false);
        $table->integer('pay');
        $table->boolean('paid')->default(false);

        $table->boolean('sunTardy')->default(false);
        $table->boolean('sunAbsent')->default(false);
        $table->string('sunTimeIn1', 5);
        $table->string('sunTimeOut1', 5);
        $table->string('sunTimeIn2', 5);
        $table->string('sunTimeOut2', 5);

        $table->boolean('monTardy')->default(false);
        $table->boolean('monAbsent')->default(false);
        $table->string('monTimeIn1', 5);
        $table->string('monTimeOut1', 5);
        $table->string('monTimeIn2', 5);
        $table->string('monTimeOut2', 5);

        $table->boolean('tueTardy')->default(false);
        $table->boolean('tueAbsent')->default(false);
        $table->string('tueTimeIn1', 5);
        $table->string('tueTimeOut1', 5);
        $table->string('tueTimeIn2', 5);
        $table->string('tueTimeOut2', 5);

        $table->boolean('wedTardy')->default(false);
        $table->boolean('wedAbsent')->default(false);
        $table->string('wedTimeIn1', 5);
        $table->string('wedTimeOut1', 5);
        $table->string('wedTimeIn2', 5);
        $table->string('wedTimeOut2', 5);

        $table->boolean('thuTardy')->default(false);
        $table->boolean('thuAbsent')->default(false);
        $table->string('thuTimeIn1', 5);
        $table->string('thuTimeOut1', 5);
        $table->string('thuTimeIn2', 5);
        $table->string('thuTimeOut2', 5);

        $table->boolean('friTardy')->default(false);
        $table->boolean('friAbsent')->default(false);
        $table->string('friTimeIn1', 5);
        $table->string('friTimeOut1', 5);
        $table->string('friTimeIn2', 5);
        $table->string('friTimeOut2', 5);

        $table->boolean('satTardy')->default(false);
        $table->boolean('satAbsent')->default(false);
        $table->string('satTimeIn1', 5);
        $table->string('satTimeOut1', 5);
        $table->string('satTimeIn2', 5);
        $table->string('satTimeOut2', 5);


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
