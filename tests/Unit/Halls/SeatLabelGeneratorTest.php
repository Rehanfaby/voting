<?php

namespace Tests\Unit\Halls;

use App\Services\Halls\SeatLabelGenerator;
use Tests\TestCase;

class SeatLabelGeneratorTest extends TestCase
{
    public function test_ground_row_label()
    {
        $this->assertSame('A-01', SeatLabelGenerator::make('GND', 'A', 1));
        $this->assertSame('J-20', SeatLabelGenerator::make('GROUND', 'J', 20));
    }

    public function test_balcony_prefix()
    {
        $this->assertSame('BAL-A-12', SeatLabelGenerator::make('BAL', 'A', 12));
    }

    public function test_vip_section_short_label()
    {
        $this->assertSame('VIP-10', SeatLabelGenerator::make('GND', 'A', 10, 'VIP'));
    }

    public function test_row_letters()
    {
        $this->assertSame('A', SeatLabelGenerator::rowLetter(0));
        $this->assertSame('Z', SeatLabelGenerator::rowLetter(25));
        $this->assertSame('AA', SeatLabelGenerator::rowLetter(26));
    }
}
