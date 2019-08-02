<?php declare(strict_types=1);

namespace Stratadox\Clock;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * DateTimeClock. A basic clock, producing immutable datetime objects.
 * For those moments when you're like: what time is it?
 *
 * @author Stratadox
 */
final class DateTimeClock implements Clock
{
    public static function create(): Clock
    {
        return new self();
    }

    public function now(): DateTimeInterface
    {
        return new DateTimeImmutable();
    }
}
