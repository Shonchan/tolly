<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('parent_id')->nullable()->default(0)->index();
			$table->string('name', 250)->nullable();
			$table->string('slug', 250)->nullable()->unique();
			$table->text('body', 65535)->nullable();
			$table->string('image', 250)->nullable();
			$table->integer('position')->nullable()->default(0);
			$table->boolean('enabled')->nullable()->default(1)->index();
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
		Schema::drop('categories');
	}

}
