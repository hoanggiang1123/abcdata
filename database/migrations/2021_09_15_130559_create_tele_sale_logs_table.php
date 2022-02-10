<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeleSaleLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tele_sale_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tele_sale_id');
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->unsignedBigInteger('updated_by_id');
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
        Schema::dropIfExists('tele_sale_logs');
    }
}
