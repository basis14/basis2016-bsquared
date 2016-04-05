<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePortfolioPathsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('portfolio_paths', function(Blueprint $table)
		{
			$table->integer('path_id', true);
			$table->integer('userID')->index('userID');
			$table->string('path', 256)->nullable();
			$table->integer('destination_id')->nullable()->index('destination_id');
			$table->primary(['path_id','userID']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('portfolio_paths');
	}

}
