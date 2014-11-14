<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddToAccounts2Table extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cloudAccounts', function(Blueprint $table)
		{
			$table->string('profileType');
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
