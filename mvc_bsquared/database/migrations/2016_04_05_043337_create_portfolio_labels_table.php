<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePortfolioLabelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('portfolio_labels', function(Blueprint $table)
		{
			$table->integer('label_id', true);
			$table->integer('userID')->index('userID');
			$table->string('label', 48)->nullable();
			$table->integer('destination_id')->nullable()->index('destination_id');
			$table->primary(['label_id','userID']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('portfolio_labels');
	}

}
