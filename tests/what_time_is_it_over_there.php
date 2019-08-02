<?php declare(strict_types=1);

namespace Stratadox\Clock\Test;

use DateTimeZone;
use PHPUnit\Framework\TestCase;
use Stratadox\Clock\TimeZoneAwareClock;

/**
 * @testdox what time is it over there
 */
class what_time_is_it_over_there extends TestCase
{
    /** @test */
    function checking_the_time_in_a_different_timezone()
    {
        $clock = TimeZoneAwareClock::in(new DateTimeZone('Europe/Amsterdam'));

        $now = $clock->now();

        $this->assertEquals('Europe/Amsterdam', $now->getTimezone()->getName());
    }

    /** @test */
    function checking_the_time_in_another_different_timezone()
    {
        $clock = TimeZoneAwareClock::in(new DateTimeZone('Asia/Dubai'));

        $now = $clock->now();

        $this->assertEquals('Asia/Dubai', $now->getTimezone()->getName());
    }

    /** @test */
    function checking_the_mutable_time_in_a_different_timezone()
    {

    }
}
