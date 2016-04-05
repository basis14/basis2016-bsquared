<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePortfolioProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('portfolio_profiles', function(Blueprint $table)
		{
			$table->integer('userID', true);
			$table->string('firstName', 48)->nullable();
			$table->string('lastName', 48)->nullable();
			$table->text('aboutMe', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('portfolio_profiles');
	}

}
