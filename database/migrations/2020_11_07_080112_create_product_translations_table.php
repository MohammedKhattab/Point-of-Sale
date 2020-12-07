<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_translations', function (Blueprint $table) {
             // mandatory fields
             $table->increments('id'); // Laravel 5.8+ use bigIncrements() instead of increments()
             $table->string('locale')->index();

             // Actual fields you want to translate
             $table->string('name');
             $table->text('description');

             // Foreign key to the main model
             $table->integer('product_id')->unsigned()->index();
             $table->unique(['product_id', 'locale']);
             $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_translations');
    }
}
