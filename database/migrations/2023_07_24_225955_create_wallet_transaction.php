<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            //BANK INFORMATION
            $table->string('account_number');
            $table->string('bank_account_holder_name');
            $table->string('bank_name');
            $table->integer('rest_money');
            $table->integer('otp_code');
            // Deposit 
            $table->integer('deposit_money');
            $table->string('deposit_trading_code');
            $table->timestamp('deposit_date_time');
            $table->string('deposit_content')->nullable();
            // withdraw 
            $table->integer('withdraw_money');
            $table->string('withdraw_trading_code');
            $table->timestamp('withdraw_date_time');
            $table->string('withdraw_content')->nullable();
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
        Schema::dropIfExists('wallet_transaction');
    }
}
