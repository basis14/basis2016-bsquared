<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPortfolioProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('portfolio_profiles', function(Blueprint $table)
		{
			$table->foreign('userID', 'portfolio_profiles_portfolio_members_userId_fk')->references('userId')->on('portfolio_members')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('portfolio_profiles', function(Blueprint $table)
		{
			$table->dropForeign('portfolio_profiles_portfolio_members_userId_fk');
		});
	}

}
