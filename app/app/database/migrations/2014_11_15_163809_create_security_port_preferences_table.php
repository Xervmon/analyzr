<?php
/**
 * Class and Function List:
 * Function list:
 * - up()
 * - (()
 * - down()
 * Classes list:
 * - CreateSecurityPortsPreferencesTable extends Migration
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePortsPreferencesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('portPreferences', function (Blueprint $table) {
            $table->increments('id');
            $table->string('project');
			$table->text('description');
			$table->text('preferences');
			$table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->softDeletes();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('portPreferences');
    }
}
