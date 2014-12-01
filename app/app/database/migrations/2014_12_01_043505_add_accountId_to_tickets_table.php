<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddAccountIdToTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create the `accounts` table
		
		Schema::table('tickets', function(Blueprint $table)
		{
			$table->integer('accountId')->unsigned()->index();
			$table->foreign('accountId')->references('id')->on('cloudAccounts')->onDelete('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Delete the `accounts` table
		Schema::table('tickets', function(Blueprint $table)
		{
			$table->dropColumn('accountId');

		});
	}

}
