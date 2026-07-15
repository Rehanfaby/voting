<?php

namespace Tests\Unit\Payments;

use App\Services\Payments\Support\CameroonPhoneNormalizer;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CameroonPhoneNormalizerTest extends TestCase
{
    public function test_local_number_is_normalized()
    {
        $this->assertSame('237675321739', CameroonPhoneNormalizer::normalize('675321739'));
        $this->assertSame('237675321739', CameroonPhoneNormalizer::normalize('0675321739'));
        $this->assertSame('237675321739', CameroonPhoneNormalizer::normalize('+237675321739'));
        $this->assertSame('237675321739', CameroonPhoneNormalizer::normalize('+237 675 321 739'));
    }

    public function test_invalid_number_is_rejected()
    {
        $this->expectException(ValidationException::class);
        CameroonPhoneNormalizer::normalize('12345');
    }

    public function test_mask_hides_middle_digits()
    {
        $this->assertSame('23767****739', CameroonPhoneNormalizer::mask('237675321739'));
    }
}
