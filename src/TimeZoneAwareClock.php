<?php declare(strict_types=1);

namespace Stratadox\Clock;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

/**
 * TimeZoneAwareClock. This clock is just like a regular clock, except that it
 * knows in which time zone it operates.
 *
 * @author Stratadox
 */
final class TimeZoneAwareClock implements Clock
{
    private $timeZone;

    public function __construct(DateTimeZone $timeZone)
    {
        $this->timeZone = $timeZone;
    }

    public static function in(DateTimeZone $timeZone): self
    {
        return new self($timeZone);
    }

    public function now(): DateTimeInterface
    {
        return new DateTimeImmutable('now', $this->timeZone);
    }
}
