<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeleSaleRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tele_sale_records', function (Blueprint $table) {
            $table->id();
            $table->string('call_sid');
            $table->string('from');
            $table->string('to')->nullable();
            $table->string('call_number')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('duration')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->string('direction')->nullable();
            $table->string('status')->nullable();
            $table->string('recording_url')->nullable();
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('tele_sale_records');
    }
}
