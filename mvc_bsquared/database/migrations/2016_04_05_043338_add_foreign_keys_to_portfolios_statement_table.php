<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPortfoliosStatementTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('portfolios_statement', function(Blueprint $table)
		{
			$table->foreign('userID', 'portfolios_statement_portfolio_members_userId_fk')->references('userId')->on('portfolio_members')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('portfolios_statement', function(Blueprint $table)
		{
			$table->dropForeign('portfolios_statement_portfolio_members_userId_fk');
		});
	}

}
