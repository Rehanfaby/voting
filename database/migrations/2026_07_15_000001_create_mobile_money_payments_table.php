<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileMoneyPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('mobile_money_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('public_reference', 64)->unique();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('payable_type', 120);
            $table->unsignedBigInteger('payable_id');
            $table->string('provider', 32);
            $table->string('provider_transaction_id', 64)->nullable();
            $table->string('provider_reference', 191)->nullable();
            $table->string('idempotency_key', 64)->nullable();
            $table->string('phone_number', 32)->nullable();
            $table->string('mobile_network', 32)->nullable();
            $table->unsignedInteger('amount');
            $table->string('currency', 3)->default('XAF');
            $table->string('status', 32)->default('created');
            $table->string('provider_status', 64)->nullable();
            $table->string('failure_code', 64)->nullable();
            $table->text('failure_message')->nullable();
            $table->longText('request_payload')->nullable();
            $table->longText('initial_response_payload')->nullable();
            $table->longText('callback_payload')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('last_status_checked_at')->nullable();
            $table->timestamps();

            $table->index(['payable_type', 'payable_id']);
            $table->unique('provider_transaction_id');
            $table->index(['provider', 'status']);
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mobile_money_payments');
    }
}
