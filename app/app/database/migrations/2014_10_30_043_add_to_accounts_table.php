<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddToAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cloudAccounts', function(Blueprint $table)
		{
			$table->string('job_id');
			$table->string('status');

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
		//Schema::drop('job_id');
		//Schema::drop('status');
	}

}
