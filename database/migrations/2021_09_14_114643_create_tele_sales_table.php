<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeleSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tele_sales', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->date('dob')->nullable();
            $table->integer('status')->default(0)->comment('0: No call | 1: No exist | 3: Not Anserwed | 4: Anserwed | 5: Not Interest | 6: Interest | 7: Registered | 8: First Deposit | 9: Second Deposit | 10: Third Deposit | 11: Rentetion');
            $table->string('username')->nullable();
            $table->unsignedBigInteger('line_id')->nullable();
            $table->string('agent')->nullable();
            $table->string('note')->nullable();
            $table->unsignedBigInteger('assign_id')->nullable();
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
        Schema::dropIfExists('tele_sales');
    }
}
