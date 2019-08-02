<?php declare(strict_types=1);

namespace Stratadox\Clock\Test;

use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Stratadox\Clock\DateTimeClock;
use Stratadox\Clock\DateTimeMutableClock;

/**
 * @testdox what time is it
 */
class what_time_is_it extends TestCase
{
    /** @test */
    function finding_out_what_time_it_is()
    {
        $clock = DateTimeClock::create();

        $now = $clock->now();

        $this->assertEqualsWithDelta(new DateTime(), $now, 1);
        $this->assertInstanceOf(DateTimeImmutable::class, $now);
    }

    /** @test */
    function finding_out_what_mutable_time_it_is()
    {
        $clock = DateTimeMutableClock::create();

        $now = $clock->now();

        $this->assertEqualsWithDelta(new DateTime(), $now, 1);
        $this->assertInstanceOf(DateTime::class, $now);
    }
}
