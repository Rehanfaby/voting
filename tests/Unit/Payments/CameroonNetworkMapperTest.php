<?php

namespace Tests\Unit\Payments;

use App\Services\Payments\Support\CameroonNetworkMapper;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CameroonNetworkMapperTest extends TestCase
{
    public function test_mtn_variants_map_to_mtn_momo_cmr()
    {
        $this->assertSame('MTN_MOMO_CMR', CameroonNetworkMapper::map('MTN'));
        $this->assertSame('MTN_MOMO_CMR', CameroonNetworkMapper::map('mtn mobile money'));
        $this->assertSame('MTN_MOMO_CMR', CameroonNetworkMapper::fromPaymentMethod('momo'));
    }

    public function test_orange_variants_map_to_orange_cmr()
    {
        $this->assertSame('ORANGE_CMR', CameroonNetworkMapper::map('ORANGE'));
        $this->assertSame('ORANGE_CMR', CameroonNetworkMapper::map('Orange Money'));
        $this->assertSame('ORANGE_CMR', CameroonNetworkMapper::fromPaymentMethod('om'));
    }

    public function test_unsupported_network_is_rejected()
    {
        $this->expectException(ValidationException::class);
        CameroonNetworkMapper::map('VODAFONE');
    }
}
