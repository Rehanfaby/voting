<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSeatMaps extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'seat_selection_enabled')) {
                $table->boolean('seat_selection_enabled')->default(false);
            }
            if (!Schema::hasColumn('products', 'seat_map_width')) {
                $table->unsignedSmallInteger('seat_map_width')->default(900);
            }
            if (!Schema::hasColumn('products', 'seat_map_height')) {
                $table->unsignedSmallInteger('seat_map_height')->default(650);
            }
        });

        Schema::create('product_seat_zones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->string('name');
            $table->decimal('price', 15, 2)->default(0);
            $table->string('color', 7)->default('#e87722');
            $table->boolean('is_vip')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_seats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('zone_id');
            $table->string('label', 32);
            $table->unsignedSmallInteger('pos_x')->default(0);
            $table->unsignedSmallInteger('pos_y')->default(0);
            $table->unsignedSmallInteger('width')->default(44);
            $table->unsignedSmallInteger('height')->default(36);
            $table->string('status', 20)->default('available');
            $table->unsignedInteger('ticket_id')->nullable();
            $table->timestamps();
            $table->unique(['product_id', 'label']);
        });

        if (Schema::hasTable('tickets') && !Schema::hasColumn('tickets', 'selected_seat_ids')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->json('selected_seat_ids')->nullable();
            });
        }

        if (Schema::hasTable('ticket_seats') && !Schema::hasColumn('ticket_seats', 'seat_label')) {
            Schema::table('ticket_seats', function (Blueprint $table) {
                $table->string('seat_label')->nullable();
                $table->unsignedBigInteger('product_seat_id')->nullable();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('product_seats');
        Schema::dropIfExists('product_seat_zones');
    }
}
