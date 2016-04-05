<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePortfolioWorksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('portfolio_works', function(Blueprint $table)
		{
			$table->integer('userID')->default(0);
			$table->integer('worksID')->default(0);
			$table->string('title', 48)->nullable();
			$table->text('projectDescription', 65535)->nullable();
			$table->string('work_link', 512)->nullable();
			$table->primary(['userID','worksID']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('portfolio_works');
	}

}
