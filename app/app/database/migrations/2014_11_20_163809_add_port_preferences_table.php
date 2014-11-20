<?php
/**
 * Class and Function List:
 * Function list:
 * - up()
 * - (()
 * - down()
 * Classes list:
 * - AddPortPreferencesTable extends Migration
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPortPreferencesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('portPreferences', function(Blueprint $table)
		{
			$table->string('job_id');
			$table->string('status');
			$table->string('wsResults');
		});
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //Schema::drop('portPreferences');
    }
}
