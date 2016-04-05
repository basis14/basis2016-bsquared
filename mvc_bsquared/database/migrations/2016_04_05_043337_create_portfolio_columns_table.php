<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePortfolioColumnsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('portfolio_columns', function(Blueprint $table)
		{
			$table->integer('column_id', true);
			$table->integer('userID')->index('userID');
			$table->text('column_text', 65535)->nullable();
			$table->integer('destination_id')->nullable()->index('destination_id');
			$table->primary(['column_id','userID']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('portfolio_columns');
	}

}
