<?php
/**
 * Class and Function List:
 * Function list:
 * - up()
 * - (()
 * - down()
 * Classes list:
 * - CreateProcessJobsTable extends Migration
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBudgetTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('budget', function (Blueprint $table) {
            $table->increments('id')->unsigned();
			$table->integer('cloudAccountId')->unsigned()->index();
			$table->enum('budgetType', array('weekly', 'monthly'));
			$table->string('budgetNotificationEmail');
            $table->float('budget');
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
    public function down() {
        Schema::drop('budget');
    }
}
