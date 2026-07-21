<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethodToVotesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('votes', 'payment_method')) {
            Schema::table('votes', function (Blueprint $table) {
                $table->string('payment_method', 16)->nullable()->after('payment_provider');
            });
        }

        // Backfill from provider / Stripe reference / linked mobile money network.
        if (Schema::hasColumn('votes', 'payment_method')) {
            DB::table('votes')
                ->where(function ($q) {
                    $q->where('payment_provider', 'stripe')
                        ->orWhere('reference', 'like', 'pi_%');
                })
                ->whereNull('payment_method')
                ->update(['payment_method' => 'card']);

            if (Schema::hasTable('mobile_money_payments')) {
                $orangeIds = DB::table('mobile_money_payments')
                    ->where(function ($q) {
                        $q->where('mobile_network', 'like', '%ORANGE%')
                            ->orWhere('request_payload', 'like', '%"payment_method":"om"%');
                    })
                    ->where('payable_type', 'like', '%vote%')
                    ->pluck('payable_id');

                if ($orangeIds->count()) {
                    DB::table('votes')
                        ->whereIn('id', $orangeIds->all())
                        ->whereNull('payment_method')
                        ->update(['payment_method' => 'om']);
                }

                $momoIds = DB::table('mobile_money_payments')
                    ->where('payable_type', 'like', '%vote%')
                    ->pluck('payable_id');

                if ($momoIds->count()) {
                    DB::table('votes')
                        ->whereIn('id', $momoIds->all())
                        ->whereNull('payment_method')
                        ->update(['payment_method' => 'momo']);
                }
            }

            DB::table('votes')
                ->whereNull('payment_method')
                ->whereIn('payment_provider', ['campay', 'pawapay'])
                ->update(['payment_method' => 'momo']);
        }
    }

    public function down()
    {
        if (Schema::hasColumn('votes', 'payment_method')) {
            Schema::table('votes', function (Blueprint $table) {
                $table->dropColumn('payment_method');
            });
        }
    }
}
