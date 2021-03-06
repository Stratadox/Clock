<?php declare(strict_types=1);

namespace Stratadox\Clock\Test;

use DateInterval;
use DateTime;
use PHPUnit\Framework\TestCase;
use Stratadox\Clock\DateTimeMutableClock;
use Stratadox\Clock\RewindableDateTimeClock;

/**
 * @testdox rewinding the clock
 */
class rewinding_the_clock extends TestCase
{
    /** @test */
    function not_rewinding_the_rewindable_clock()
    {
        $clock = RewindableDateTimeClock::create();

        self::assertEqualsWithDelta(new DateTime(), $clock->now(), 1);
    }

    /** @test */
    function rewinding_the_clock_by_five_minutes()
    {
        $clock = RewindableDateTimeClock::create();

        $clock = $clock->rewind(new DateInterval('PT5M'));

        self::assertEqualsWithDelta(new DateTime('-5 minutes'), $clock->now(), 1);
    }

    /** @test */
    function rewinding_the_clock_by_ten_days()
    {
        $clock = RewindableDateTimeClock::create();

        $clock = $clock->rewind(new DateInterval('P10D'));

        self::assertEqualsWithDelta(new DateTime('-10 days'), $clock->now(), 1);
    }

    /** @test */
    function rewinding_the_clock_by_five_minutes_twice()
    {
        $clock = RewindableDateTimeClock::create()
            ->rewind(new DateInterval('PT5M'))
            ->rewind(new DateInterval('PT5M'));

        self::assertEqualsWithDelta(new DateTime('-10 minutes'), $clock->now(), 1);
    }

    /** @test */
    function reusing_a_rewinded_clock()
    {
        $clock = RewindableDateTimeClock::create()
            ->rewind(new DateInterval('PT5M'));

        $otherClock = $clock->rewind(new DateInterval('PT5M'));

        self::assertEqualsWithDelta(new DateTime('-5 minutes'), $clock->now(), 1);
        self::assertEqualsWithDelta(new DateTime('-10 minutes'), $otherClock->now(), 1);
    }

    /** @test */
    function making_mutable_datetime_objects()
    {
        $clock = RewindableDateTimeClock::using(DateTimeMutableClock::create())
            ->rewind(new DateInterval('P10D'));

        self::assertInstanceOf(DateTime::class, $clock->now());
    }
}
