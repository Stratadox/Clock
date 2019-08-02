<?php declare(strict_types=1);

namespace Stratadox\Clock;

use DateTimeInterface;

/**
 * UnmovingClock. This clock has stopped ticking.
 * It stands still at a given point in time. Like the one at grandma's.
 * Bit sad for the clock, but very nice for testing.
 *
 * @author Stratadox
 */
final class UnmovingClock implements Clock
{
    private $when;

    private function __construct(DateTimeInterface $when)
    {
        $this->when = $when;
    }

    public static function standingStillAt(DateTimeInterface $dateTime): Clock
    {
        return new self($dateTime);
    }

    public function now(): DateTimeInterface
    {
        return $this->when;
    }
}
