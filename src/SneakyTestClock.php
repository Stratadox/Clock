<?php declare(strict_types=1);

namespace Stratadox\Clock;

use DateInterval;
use DateTimeInterface;

/**
 * SneakyTestClock. This clock is very sneaky. Only use it in your test code.
 * The basic idea is you inject a clock into your service, in production that's
 * a regular clock, and for testing you can inject this one instead and simulate
 * the passing of time by altering the clock instance(s).
 *
 * @author Stratadox
 */
final class SneakyTestClock implements RewindableClock
{
    /** @var RewindableClock */
    private $clock;

    private static $rewinds;
    private static $fastForwards;
    private static $sneaky;

    private function __construct(RewindableClock $clock)
    {
        $this->clock = $clock;
        self::$rewinds = [];
        self::$fastForwards = [];
        self::$sneaky = null;
    }

    public static function create(): self
    {
        return new self(RewindableDateTimeClock::create());
    }

    public static function using(RewindableClock $clock): self
    {
        return new self($clock);
    }

    public function now(): DateTimeInterface
    {
        $datetime = (self::$sneaky ?: $this->clock)->now();
        foreach (self::$rewinds as $rewind) {
            $datetime = $datetime->sub($rewind);
        }
        foreach (self::$fastForwards as $fastForward) {
            $datetime = $datetime->add($fastForward);
        }
        return $datetime;
    }

    public function rewind(DateInterval $interval): RewindableClock
    {
        $new = clone $this;
        $new->clock = $this->clock->rewind($interval);
        return $new;
    }

    public function fastForward(DateInterval $interval): RewindableClock
    {
        $new = clone $this;
        $new->clock = $this->clock->fastForward($interval);
        return $new;
    }

    public function sneakilySetClock(RewindableClock $clock): void
    {
        self::$sneaky = $clock;
    }

    public function sneakBack(DateInterval $interval): void
    {
        self::$rewinds[] = $interval;
    }

    public function sneakForwards(DateInterval $interval): void
    {
        self::$fastForwards[] = $interval;
    }
}
