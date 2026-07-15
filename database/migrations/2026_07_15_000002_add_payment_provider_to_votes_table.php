<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentProviderToVotesTable extends Migration
{
    public function up()
    {
        Schema::table('votes', function (Blueprint $table) {
            if (!Schema::hasColumn('votes', 'payment_provider')) {
                $table->string('payment_provider', 32)->nullable()->after('reference');
            }
            if (!Schema::hasColumn('votes', 'mobile_money_payment_id')) {
                $table->unsignedBigInteger('mobile_money_payment_id')->nullable()->after('payment_provider');
            }
        });
    }

    public function down()
    {
        Schema::table('votes', function (Blueprint $table) {
            if (Schema::hasColumn('votes', 'mobile_money_payment_id')) {
                $table->dropColumn('mobile_money_payment_id');
            }
            if (Schema::hasColumn('votes', 'payment_provider')) {
                $table->dropColumn('payment_provider');
            }
        });
    }
}
