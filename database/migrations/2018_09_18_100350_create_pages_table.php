<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 250)->nullable();
			$table->string('slug', 250)->nullable()->index();
			$table->text('body', 65535)->nullable();
			$table->boolean('enabled')->nullable()->default(1)->index();
			$table->string('header', 500)->nullable();
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
		Schema::drop('pages');
	}

}
