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

class CreateCloudAccountLogsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('cloudAccountLogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('job_id');
			$table->integer('cloudAccountId')->unsigned()->index();
			$table->text('params');
            $table->string('status');
			$table->text('result');
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->integer('cloudAccountId')->unsigned()->index();
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
    public function down() {
        Schema::drop('cloudAccountLogs');
    }
}
