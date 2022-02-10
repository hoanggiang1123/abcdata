<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInitCountColumnToEnterLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enter_links', function (Blueprint $table) {
            $table->unsignedBigInteger('init_vote_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enter_links', function (Blueprint $table) {
            $table->dropColumn('init_vote_count');
        });
    }
}
