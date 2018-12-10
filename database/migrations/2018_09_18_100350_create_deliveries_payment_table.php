<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveriesPaymentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('deliveries_payment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('delivery_id')->nullable()->index('delivery_payment_delivery_id_index');
			$table->integer('payment_method_id')->nullable()->index('delivery_payment_payment_method_id_index');
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
		Schema::drop('deliveries_payment');
	}

}
