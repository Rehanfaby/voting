<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDynamicHallSeatingTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('halls')) {
            Schema::create('halls', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('city')->nullable();
                $table->string('address')->nullable();
                $table->text('notes')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('hall_layout_versions')) {
            Schema::create('hall_layout_versions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('hall_id');
                $table->unsignedInteger('version')->default(1);
                $table->string('status', 20)->default('draft'); // draft|published
                $table->string('label')->nullable();
                $table->unsignedSmallInteger('canvas_width')->default(1000);
                $table->unsignedSmallInteger('canvas_height')->default(700);
                $table->string('background_image')->nullable();
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
                $table->unique(['hall_id', 'version']);
            });
        }

        if (!Schema::hasTable('hall_levels')) {
            Schema::create('hall_levels', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('hall_layout_version_id');
                $table->string('code', 32);
                $table->string('name');
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();
                $table->unique(['hall_layout_version_id', 'code'], 'hall_levels_version_code_unique');
            });
        }

        if (!Schema::hasTable('hall_sections')) {
            Schema::create('hall_sections', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('hall_layout_version_id');
                $table->unsignedBigInteger('hall_level_id');
                $table->string('code', 32);
                $table->string('name');
                $table->string('type', 32)->default('seating'); // seating|standing|stage|aisle|restricted|door|pillar|screen
                $table->unsignedSmallInteger('pos_x')->default(0);
                $table->unsignedSmallInteger('pos_y')->default(0);
                $table->unsignedSmallInteger('width')->default(200);
                $table->unsignedSmallInteger('height')->default(120);
                $table->string('color', 7)->nullable();
                $table->text('polygon_json')->nullable();
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();
                $table->unique(['hall_layout_version_id', 'code'], 'hall_sections_version_code_unique');
            });
        }

        if (!Schema::hasTable('hall_rows')) {
            Schema::create('hall_rows', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('hall_section_id');
                $table->string('label', 32);
                $table->string('curve_type', 20)->default('straight'); // straight|curved|fan
                $table->text('curve_params_json')->nullable();
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('hall_template_seats')) {
            Schema::create('hall_template_seats', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('hall_layout_version_id');
                $table->unsignedBigInteger('hall_level_id')->nullable();
                $table->unsignedBigInteger('hall_section_id')->nullable();
                $table->unsignedBigInteger('hall_row_id')->nullable();
                $table->unsignedSmallInteger('seat_index')->default(1);
                $table->string('label', 64);
                $table->unsignedSmallInteger('pos_x')->default(0);
                $table->unsignedSmallInteger('pos_y')->default(0);
                $table->unsignedSmallInteger('width')->default(28);
                $table->unsignedSmallInteger('height')->default(28);
                $table->string('seat_type', 20)->default('seat'); // seat|table|standing
                $table->boolean('is_accessible')->default(false);
                $table->boolean('restricted_view')->default(false);
                $table->timestamps();
                $table->unique(['hall_layout_version_id', 'label'], 'hall_template_seats_version_label_unique');
            });
        }

        if (!Schema::hasTable('event_seat_maps')) {
            Schema::create('event_seat_maps', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('product_id');
                $table->unsignedBigInteger('hall_layout_version_id');
                $table->string('status', 20)->default('draft'); // draft|published|locked
                $table->unsignedSmallInteger('canvas_width')->default(1000);
                $table->unsignedSmallInteger('canvas_height')->default(700);
                $table->string('background_image')->nullable();
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
                $table->unique('product_id');
            });
        }

        if (!Schema::hasTable('event_seat_categories')) {
            Schema::create('event_seat_categories', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('event_seat_map_id');
                $table->string('code', 32);
                $table->string('name');
                $table->decimal('price', 15, 2)->default(0);
                $table->string('color', 7)->default('#e87722');
                $table->boolean('is_vip')->default(false);
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();
                $table->unique(['event_seat_map_id', 'code'], 'event_seat_categories_map_code_unique');
            });
        }

        if (!Schema::hasTable('event_seat_inventory')) {
            Schema::create('event_seat_inventory', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('event_seat_map_id');
                $table->unsignedBigInteger('hall_template_seat_id')->nullable();
                $table->unsignedBigInteger('event_seat_category_id')->nullable();
                $table->string('label', 64);
                $table->string('level_code', 32)->nullable();
                $table->string('level_name')->nullable();
                $table->string('section_code', 32)->nullable();
                $table->string('section_name')->nullable();
                $table->string('row_label', 32)->nullable();
                $table->unsignedSmallInteger('seat_index')->default(1);
                $table->unsignedSmallInteger('pos_x')->default(0);
                $table->unsignedSmallInteger('pos_y')->default(0);
                $table->unsignedSmallInteger('width')->default(28);
                $table->unsignedSmallInteger('height')->default(28);
                $table->string('seat_type', 20)->default('seat');
                $table->boolean('is_accessible')->default(false);
                $table->boolean('restricted_view')->default(false);
                $table->decimal('price', 15, 2)->default(0);
                $table->string('status', 20)->default('available'); // available|held|sold|blocked
                $table->timestamp('held_until')->nullable();
                $table->string('hold_token', 64)->nullable();
                $table->unsignedInteger('ticket_id')->nullable();
                $table->timestamps();
                $table->unique(['event_seat_map_id', 'label'], 'event_seat_inventory_map_label_unique');
                $table->index(['event_seat_map_id', 'status']);
                $table->index(['hold_token']);
                $table->index(['held_until']);
            });
        }

        if (Schema::hasTable('ticket_seats')) {
            Schema::table('ticket_seats', function (Blueprint $table) {
                if (!Schema::hasColumn('ticket_seats', 'event_seat_inventory_id')) {
                    $table->unsignedBigInteger('event_seat_inventory_id')->nullable()->after('product_seat_id');
                }
                if (!Schema::hasColumn('ticket_seats', 'seat_location')) {
                    $table->string('seat_location')->nullable()->after('seat_label');
                }
            });
        }

        if (Schema::hasTable('tickets') && !Schema::hasColumn('tickets', 'hold_token')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('hold_token', 64)->nullable()->after('selected_seat_ids');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('tickets') && Schema::hasColumn('tickets', 'hold_token')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->dropColumn('hold_token');
            });
        }
        if (Schema::hasTable('ticket_seats')) {
            Schema::table('ticket_seats', function (Blueprint $table) {
                if (Schema::hasColumn('ticket_seats', 'seat_location')) {
                    $table->dropColumn('seat_location');
                }
                if (Schema::hasColumn('ticket_seats', 'event_seat_inventory_id')) {
                    $table->dropColumn('event_seat_inventory_id');
                }
            });
        }
        Schema::dropIfExists('event_seat_inventory');
        Schema::dropIfExists('event_seat_categories');
        Schema::dropIfExists('event_seat_maps');
        Schema::dropIfExists('hall_template_seats');
        Schema::dropIfExists('hall_rows');
        Schema::dropIfExists('hall_sections');
        Schema::dropIfExists('hall_levels');
        Schema::dropIfExists('hall_layout_versions');
        Schema::dropIfExists('halls');
    }
}
