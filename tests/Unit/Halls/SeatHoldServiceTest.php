<?php

namespace Tests\Unit\Halls;

use App\EventSeatCategory;
use App\EventSeatInventory;
use App\EventSeatMap;
use App\Product;
use App\Services\Halls\SeatHoldService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Lightweight hold/double-book coverage without full RefreshDatabase
 * (legacy migrations are not SQLite-clean in this repo).
 */
class SeatHoldServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createMinimalTables();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('event_seat_inventory');
        Schema::dropIfExists('event_seat_categories');
        Schema::dropIfExists('event_seat_maps');
        Schema::dropIfExists('products');
        parent::tearDown();
    }

    public function test_second_hold_on_same_seat_fails()
    {
        $ctx = $this->seedSeat();
        $holds = app(SeatHoldService::class);
        $first = $holds->createHold($ctx['product'], [$ctx['seat']->id]);
        $this->assertNotEmpty($first['hold_token']);

        $this->expectException(\RuntimeException::class);
        $holds->createHold($ctx['product'], [$ctx['seat']->id]);
    }

    public function test_expired_hold_is_released_and_can_be_retaken()
    {
        $ctx = $this->seedSeat();
        $seat = $ctx['seat'];
        $seat->update([
            'status' => 'held',
            'hold_token' => 'old',
            'held_until' => now()->subMinute(),
        ]);

        $count = app(SeatHoldService::class)->releaseExpired();
        $this->assertGreaterThanOrEqual(1, $count);
        $this->assertSame('available', $seat->fresh()->status);

        $again = app(SeatHoldService::class)->createHold($ctx['product'], [$seat->id]);
        $this->assertNotSame('old', $again['hold_token']);
    }

    public function test_price_sum_from_hold()
    {
        $ctx = $this->seedSeat(7500);
        $hold = app(SeatHoldService::class)->createHold($ctx['product'], [$ctx['seat']->id]);
        $this->assertEquals(7500.0, $hold['total']);
    }

    protected function createMinimalTables()
    {
        Schema::dropIfExists('event_seat_inventory');
        Schema::dropIfExists('event_seat_categories');
        Schema::dropIfExists('event_seat_maps');
        Schema::dropIfExists('products');

        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->boolean('seat_selection_enabled')->default(false);
            $table->timestamps();
        });

        Schema::create('event_seat_maps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('product_id');
            $table->unsignedBigInteger('hall_layout_version_id')->nullable();
            $table->string('status', 20)->default('published');
            $table->unsignedSmallInteger('canvas_width')->default(800);
            $table->unsignedSmallInteger('canvas_height')->default(600);
            $table->string('background_image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('event_seat_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('event_seat_map_id');
            $table->string('code', 32);
            $table->string('name');
            $table->decimal('price', 15, 2)->default(0);
            $table->string('color', 7)->nullable();
            $table->boolean('is_vip')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

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
            $table->string('status', 20)->default('available');
            $table->timestamp('held_until')->nullable();
            $table->string('hold_token', 64)->nullable();
            $table->unsignedInteger('ticket_id')->nullable();
            $table->timestamps();
        });
    }

    protected function seedSeat($price = 5000)
    {
        $product = Product::create([
            'name' => 'Test Event',
            'price' => $price,
            'seat_selection_enabled' => true,
        ]);

        $map = EventSeatMap::create([
            'product_id' => $product->id,
            'hall_layout_version_id' => 1,
            'status' => 'published',
            'canvas_width' => 800,
            'canvas_height' => 600,
            'published_at' => now(),
        ]);

        $cat = EventSeatCategory::create([
            'event_seat_map_id' => $map->id,
            'code' => 'REGULAR',
            'name' => 'Regular',
            'price' => $price,
            'color' => '#3b82f6',
        ]);

        $seat = EventSeatInventory::create([
            'event_seat_map_id' => $map->id,
            'event_seat_category_id' => $cat->id,
            'label' => 'A-01',
            'price' => $price,
            'status' => 'available',
            'level_name' => 'Ground',
            'section_name' => 'Main',
            'row_label' => 'A',
        ]);

        return compact('product', 'map', 'seat');
    }
}
