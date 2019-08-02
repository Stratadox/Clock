<?php declare(strict_types=1);

namespace Stratadox\Clock;

use DateInterval;
use DateTimeInterface;

/**
 * RewindableDateTimeClock. This clock can be rewinded- or fast-forwarded as
 * needed. This implementation is immutable; alterations to the time offset
 * apply only to the returned copy of the clock, the original instance is not
 * affected.
 *
 * @author Stratadox
 */
final class RewindableDateTimeClock implements RewindableClock
{
    private $clock;
    private $rewinds = [];
    private $fastForwards = [];

    private function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    public static function create(): RewindableClock
    {
        return new self(DateTimeClock::create());
    }

    public static function using(Clock $originalClock): RewindableClock
    {
        return new self($originalClock);
    }

    public function rewind(DateInterval $interval): RewindableClock
    {
        $new = clone $this;
        $new->rewinds[] = $interval;
        return $new;
    }

    public function fastForward(DateInterval $interval): RewindableClock
    {
        $new = clone $this;
        $new->fastForwards[] = $interval;
        return $new;
    }

    public function now(): DateTimeInterface
    {
        $datetime = $this->clock->now();
        foreach ($this->rewinds as $rewind) {
            $datetime = $datetime->sub($rewind);
        }
        foreach ($this->fastForwards as $fastForward) {
            $datetime = $datetime->add($fastForward);
        }
        return $datetime;
    }
}
