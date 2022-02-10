<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePluginReposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugin_repos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('author')->nullable();
            $table->string('author_profile')->nullable();
            $table->string('version');
            $table->string('download_url');
            $table->string('requires');
            $table->string('tested')->nullable();
            $table->string('requires_php')->nullable();
            $table->text('sections')->nullable();
            $table->text('banners')->nullable();
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
        Schema::dropIfExists('plugin_repos');
    }
}
