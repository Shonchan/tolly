<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('deliveries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 250)->nullable();
			$table->text('description', 65535)->nullable();
			$table->decimal('free_from', 10, 0)->nullable();
			$table->decimal('price', 10, 0)->nullable();
			$table->boolean('enabled')->nullable();
			$table->integer('position')->nullable()->index('delivery_position_index');
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
		Schema::drop('deliveries');
	}

}
