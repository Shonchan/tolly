<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVariantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('variants', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id')->nullable()->index();
			$table->string('sku', 250)->nullable()->index();
			$table->string('name', 250)->nullable();
			$table->decimal('price', 10, 0)->nullable()->index();
			$table->decimal('compare_price', 10, 0)->nullable();
			$table->integer('stock')->nullable()->index();
			$table->integer('position')->nullable()->index();
			$table->string('external_id', 36)->nullable()->index();
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
		Schema::drop('variants');
	}

}
