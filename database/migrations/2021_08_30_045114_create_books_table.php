<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('genre');
            $table->bigInteger('author_id', false, true);
            $table->string('isbn');
            $table->date('release_date');
            $table->string('olang');
            $table->string('langs');
            $table->text('descrip')->nullable();
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('authors')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
