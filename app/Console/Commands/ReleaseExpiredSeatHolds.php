<?php

namespace App\Console\Commands;

use App\Services\Halls\SeatHoldService;
use Illuminate\Console\Command;

class ReleaseExpiredSeatHolds extends Command
{
    protected $signature = 'seats:release-expired-holds';

    protected $description = 'Release expired event seat holds back to available';

    public function handle(SeatHoldService $holds)
    {
        $count = $holds->releaseExpired();
        $this->info('Released ' . (int) $count . ' expired seat hold(s).');

        return 0;
    }
}
