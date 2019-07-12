<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog__tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('name');
            $table->json('slug');
            $table->timestamps();
        });

        Schema::create('blog__tag_post', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tag_id')->unsigned();
            $table->bigInteger('post_id')->unsigned();
            $table->foreign('tag_id')
                ->references('id')
                ->on('blog__tags')
                ->onDelete('cascade');
            $table->foreign('post_id')
                ->references('id')
                ->on('blog__posts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog__tags');
        Schema::dropIfExists('blog__tag_post');
    }
}
