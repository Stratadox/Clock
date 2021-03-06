<?php declare(strict_types=1);

namespace Stratadox\Clock\Test;

use DateInterval;
use DateTime;
use PHPUnit\Framework\TestCase;
use Stratadox\Clock\RewindableDateTimeClock;

/**
 * @testdox fast-forwarding the clock
 */
class fast_forwarding_the_clock extends TestCase
{
    /** @test */
    function fast_forward_by_ten_minutes()
    {
        $clock = RewindableDateTimeClock::create();

        $clock = $clock->fastForward(new DateInterval('PT10M'));

        self::assertEqualsWithDelta(new DateTime('+10 minutes'), $clock->now(), 1);
    }

    /** @test */
    function fast_forward_by_ten_minutes_twice()
    {
        $clock = RewindableDateTimeClock::create()
            ->fastForward(new DateInterval('PT10M'))
            ->fastForward(new DateInterval('PT10M'));

        self::assertEqualsWithDelta(new DateTime('+20 minutes'), $clock->now(), 1);
    }

    /** @test */
    function reusing_a_fast_forwarded_clock()
    {
        $clock = RewindableDateTimeClock::create()
            ->fastForward(new DateInterval('PT5M'));

        $otherClock = $clock->fastForward(new DateInterval('PT5M'));

        self::assertEqualsWithDelta(new DateTime('+5 minutes'), $clock->now(), 1);
        self::assertEqualsWithDelta(new DateTime('+10 minutes'), $otherClock->now(), 1);
    }

    /** @test */
    function rewinding_a_fast_forwarded_clock()
    {
        $clock = RewindableDateTimeClock::create()
            ->fastForward(new DateInterval('PT5M'))
            ->rewind(new DateInterval('PT5M'));

        self::assertEqualsWithDelta(new DateTime(), $clock->now(), 1);
    }
}
