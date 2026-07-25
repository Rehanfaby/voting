<?php

namespace App\Services\Halls;

class SeatLabelGenerator
{
    /**
     * Build a seat label like A-01, BAL-A-12, VIP-10.
     */
    public static function make($levelCode, $rowLabel, $seatIndex, $sectionCode = null)
    {
        $seat = str_pad((string) max(1, (int) $seatIndex), 2, '0', STR_PAD_LEFT);
        $row = strtoupper(trim((string) $rowLabel));
        $level = strtoupper(trim((string) $levelCode));
        $section = strtoupper(trim((string) ($sectionCode ?? '')));

        if ($section !== '' && in_array($section, ['VIP', 'CIP', 'PREMIUM', 'PLATINUM'], true)) {
            return $section . '-' . (int) $seatIndex;
        }

        if ($level !== '' && $level !== 'GND' && $level !== 'GROUND') {
            return $level . '-' . $row . '-' . $seat;
        }

        return $row . '-' . $seat;
    }

    /** Row letters: A, B, … Z, AA, AB… */
    public static function rowLetter($index)
    {
        $index = max(0, (int) $index);
        $letter = '';
        do {
            $letter = chr(65 + ($index % 26)) . $letter;
            $index = intdiv($index, 26) - 1;
        } while ($index >= 0);

        return $letter;
    }
}
