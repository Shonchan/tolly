<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePopularityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('popularity', function(Blueprint $table)
		{
			$table->integer('product_id')->nullable()->index();
			$table->integer('geo_id')->nullable()->index();
			$table->integer('weight')->nullable()->index();
			$table->unique(['geo_id','product_id'], 'geo_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('popularity');
	}

}
