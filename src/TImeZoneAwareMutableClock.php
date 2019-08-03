<?php declare(strict_types=1);

namespace Stratadox\Clock;

use DateTime;
use DateTimeInterface;
use DateTimeZone;

/**
 * TImeZoneAwareMutableClock. This clock is just like a regular mutable clock,
 * except that it knows in which time zone it operates.
 *
 * @author Stratadox
 */
final class TImeZoneAwareMutableClock implements Clock
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
        return new DateTime('now', $this->timeZone);
    }
}
