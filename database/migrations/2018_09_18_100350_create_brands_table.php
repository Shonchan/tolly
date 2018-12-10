<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBrandsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('brands', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 250)->nullable()->index();
			$table->string('slug', 250)->nullable()->index();
			$table->text('description', 65535)->nullable();
			$table->string('image', 250)->nullable();
			$table->timestamps();
			$table->string('meta_title', 250)->nullable();
			$table->string('meta_keywords', 250)->nullable();
			$table->string('meta_description', 250)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('brands');
	}

}
