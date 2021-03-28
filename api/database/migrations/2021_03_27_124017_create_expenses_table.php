<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('expense_category_id')->nullable();
            $table->unsignedBigInteger('wallet_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->text('description');
            $table->datetime('time_made');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('wallet_id')
                ->references('id')
                ->on('wallets')
                ->onDelete('set null');

            $table->foreign('expense_category_id')
                ->references('id')
                ->on('expense_categories')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
