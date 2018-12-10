<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMarksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('marks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 250)->nullable()->index();
			$table->string('seo_text', 250)->nullable();
			$table->integer('category_id')->nullable()->index();
			$table->string('slug', 250)->nullable()->index();
			$table->boolean('enabled')->nullable()->index();
			$table->integer('position')->nullable();
			$table->text('filter', 65535)->nullable();
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
		Schema::drop('marks');
	}

}
