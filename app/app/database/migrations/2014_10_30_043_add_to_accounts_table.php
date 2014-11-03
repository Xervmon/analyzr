<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddToAccounts1Table extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cloudAccounts', function(Blueprint $table)
		{
			$table->text('wsResults');
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
		Schema::drop('wsResults');
		//Schema::drop('status');
	}

}
