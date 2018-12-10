<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchases', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('order_id')->nullable()->index();
			$table->integer('product_id')->nullable()->index();
			$table->integer('variant_id')->nullable()->index();
			$table->string('product_name', 250)->nullable();
			$table->string('variant_name', 250)->nullable();
			$table->decimal('price', 10, 0)->nullable();
			$table->integer('amount')->nullable();
			$table->string('sku', 250)->nullable();
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
		Schema::drop('purchases');
	}

}
