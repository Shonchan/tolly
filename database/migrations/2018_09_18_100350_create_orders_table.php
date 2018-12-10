<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('delivery_id')->nullable();
			$table->decimal('delivery_price', 10, 0)->nullable();
			$table->integer('payment_method_id')->nullable();
			$table->boolean('paid')->nullable()->default(1)->index();
			$table->dateTime('payment_date')->nullable();
			$table->boolean('closed')->nullable()->index();
			$table->integer('user_id')->nullable()->index();
			$table->string('name', 250)->nullable();
			$table->string('address', 250)->nullable();
			$table->string('phone', 250)->nullable();
			$table->string('email', 250)->nullable();
			$table->string('comment', 1000)->nullable();
			$table->boolean('status')->nullable()->index();
			$table->string('slug', 250)->nullable();
			$table->text('payment_details', 65535)->nullable();
			$table->string('ip', 15)->nullable();
			$table->decimal('total_price', 10, 0)->nullable();
			$table->string('note', 1000)->nullable();
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
		Schema::drop('orders');
	}

}
