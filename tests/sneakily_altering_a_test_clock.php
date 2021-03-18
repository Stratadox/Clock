<?php declare(strict_types=1);

namespace Stratadox\Clock\Test;

use DateInterval;
use DateTime;
use PHPUnit\Framework\TestCase;
use Stratadox\Clock\DateTimeMutableClock;
use Stratadox\Clock\RewindableDateTimeClock;
use Stratadox\Clock\SneakyTestClock;

/**
 * @testdox sneakily altering a test clock
 */
class sneakily_altering_a_test_clock extends TestCase
{
    /** @test */
    function sneaking_the_test_clock_instance_forward()
    {
        $clock = SneakyTestClock::create();

        $clock->sneakForwards(new DateInterval('PT10M'));

        self::assertEqualsWithDelta(new DateTime('+10 minutes'), $clock->now(), 1);
    }

    /** @test */
    function sneaking_the_test_clock_instance_backwards()
    {
        $clock = SneakyTestClock::using(RewindableDateTimeClock::create());

        $clock->sneakBack(new DateInterval('PT10M'));

        self::assertEqualsWithDelta(new DateTime('-10 minutes'), $clock->now(), 1);
    }

    /** @test */
    function wrapping_the_test_clock_around_a_mutable_datetime_clock()
    {
        $clock = SneakyTestClock::using(RewindableDateTimeClock::using(DateTimeMutableClock::create()));

        self::assertInstanceOf(DateTime::class, $clock->now());
    }

    /** @test */
    function sneakily_changing_the_clock_instance()
    {
        $clock = SneakyTestClock::using(RewindableDateTimeClock::using(DateTimeMutableClock::create()));

        $clock->sneakilySetClock(RewindableDateTimeClock::create());

        self::assertNotInstanceOf(DateTime::class, $clock->now());
    }

    /** @test */
    function regularly_fast_forwarding_and_then_sneaking_back()
    {
        $originalTestClock = SneakyTestClock::create();

        $newClockInstance = $originalTestClock->fastForward(new DateInterval('PT10M'));

        $originalTestClock->sneakBack(new DateInterval('PT10M'));

        // Different instances, one fast-forwarded, both sneaked back
        self::assertEqualsWithDelta(new DateTime('-10 minutes'), $originalTestClock->now(), 1);
        self::assertEqualsWithDelta(new DateTime(), $newClockInstance->now(), 1, 'New instance sneaks back too');
    }

    /** @test */
    function regularly_rewinding_and_then_sneaking_forwards()
    {
        $originalTestClock = SneakyTestClock::create();

        $newClockInstance = $originalTestClock->rewind(new DateInterval('PT10M'));

        $originalTestClock->sneakForwards(new DateInterval('PT10M'));

        // Different instances, one rewinded, both sneaked forwards
        self::assertEqualsWithDelta(new DateTime('+10 minutes'), $originalTestClock->now(), 1);
        self::assertEqualsWithDelta(new DateTime(), $newClockInstance->now(), 1, 'New instance sneaks forward too');
    }

    /** @test */
    function regularly_rewinding_and_fast_forwarding_then_sneakily_setting_the_clock()
    {
        $originalTestClock = SneakyTestClock::create();

        $rewindedClockInstance = $originalTestClock->rewind(new DateInterval('PT10M'));
        $fastForwardedClockInstance = $rewindedClockInstance->fastForward(new DateInterval('PT20M'));

        $originalTestClock->sneakilySetClock(RewindableDateTimeClock::create());

        // Different instances, all overruled by the sneaky clock overruling - is this what we want?
        self::assertEqualsWithDelta(new DateTime(), $originalTestClock->now(), 1);
        self::assertEqualsWithDelta(new DateTime(), $rewindedClockInstance->now(), 1);
        self::assertEqualsWithDelta(new DateTime(), $fastForwardedClockInstance->now(), 1);
    }
}
