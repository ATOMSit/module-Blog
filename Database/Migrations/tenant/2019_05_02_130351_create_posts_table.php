<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog__posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('title');
            $table->json('slug');
            $table->json('body');
            $table->boolean('online')
                ->default(1);
            $table->boolean('indexable')
                ->default((1));
            $table->morphs('author');
            $table->dateTime('published_at');
            $table->dateTime('unpublished_at')
                ->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog__posts');
    }
}
