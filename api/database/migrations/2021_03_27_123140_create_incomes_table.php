<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2);
            $table->text('description');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('wallet_id')->nullable();
            $table->unsignedBigInteger('income_source_id')->nullable();
            $table->datetime('time_received');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('wallet_id')
                ->references('id')
                ->on('wallets')
                ->onDelete('set null');

            $table->foreign('income_source_id')
                ->references('id')
                ->on('income_sources')
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
        Schema::dropIfExists('incomes');
    }
}
