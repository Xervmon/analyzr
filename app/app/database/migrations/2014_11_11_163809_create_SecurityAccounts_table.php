<?php
/**
 * Class and Function List:
 * Function list:
 * - up()
 * - (()
 * - down()
 * Classes list:
 * - CreateCloudAccountsTable extends Migration
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSecurityAccountsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('securityAccounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
			$table->string('cloudProvider');
            $table->text('credentials');
			$table->text('wsResults');
			$table->string('job_id');
			$table->string('status');
			
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
        Schema::drop('securityAccounts');
    }
}
