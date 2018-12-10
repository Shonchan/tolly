<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('slug', 250)->nullable()->index();
			$table->integer('brand_id')->nullable()->index();
			$table->string('name', 250)->nullable()->index();
			$table->text('annotation', 65535)->nullable();
			$table->text('body')->nullable();
			$table->boolean('enabled')->nullable()->index();
			$table->string('external_name', 250)->nullable()->index();
			$table->integer('provider_id')->nullable()->index();
			$table->timestamps();
			$table->string('images', 250)->nullable();
			$table->string('meta_title', 500)->nullable();
			$table->string('meta_keywords', 500)->nullable();
			$table->string('meta_description', 500)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products');
	}

}
