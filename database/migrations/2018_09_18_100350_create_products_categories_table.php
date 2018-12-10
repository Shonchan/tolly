<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id')->nullable()->index();
			$table->integer('category_id')->nullable()->index();
			$table->timestamps();
			$table->unique(['product_id','category_id'], 'product_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_categories');
	}

}
