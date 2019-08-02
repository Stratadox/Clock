<?php declare(strict_types=1);

namespace Stratadox\Clock;

use DateTime;
use DateTimeInterface;

/**
 * DateTimeMutableClock. A basic clock, producing mutable datetime objects.
 * Use the normal clock whenever possible (datetime should not be mutable).
 * Only included because it's not always possible to change DateTime type hints
 * into DateTimeInterface. If you can, program to the interface instead.
 *
 * @author Stratadox
 */
final class DateTimeMutableClock implements Clock
{
    public static function create(): Clock
    {
        return new self();
    }

    public function now(): DateTimeInterface
    {
        return new DateTime();
    }
}
