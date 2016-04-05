<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPortfolioPathsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('portfolio_paths', function(Blueprint $table)
		{
			$table->foreign('destination_id', 'portfolio_paths_ibfk_2')->references('destination_id')->on('file_path_lookup')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('userID', 'portfolio_paths_portfolio_members_userId_fk')->references('userId')->on('portfolio_members')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('portfolio_paths', function(Blueprint $table)
		{
			$table->dropForeign('portfolio_paths_ibfk_2');
			$table->dropForeign('portfolio_paths_portfolio_members_userId_fk');
		});
	}

}
