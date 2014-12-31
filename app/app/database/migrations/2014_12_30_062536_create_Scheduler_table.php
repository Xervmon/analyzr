<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	  Schema::create('scheduler', function (Blueprint $table) {
            $table->increments('id')->unsigned();
			$table->integer('cloudAccountId')->unsigned()->index();
			$table->string('service');
			$table->string('region');
			$table->string('instance');
			$table->timestamp('scheduler_starts_on');
			$table->enum('scheduler_update', array('dialy', 'weekly', 'monthly', 'yearly'));
            $table->string('scheduler_status');
            $table->string('schedulerNotificationEmail');
			$table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('cloudAccountId')->references('id')->on('cloudAccounts')->onDelete('cascade');
			$table->softDeletes();
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('scheduler');
	}

}
