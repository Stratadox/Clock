<?php declare(strict_types=1);

namespace Stratadox\Clock\Test;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use Stratadox\Clock\TimeZoneAwareClock;
use Stratadox\Clock\TimeZoneAwareMutableClock;

/**
 * @testdox what time is it over there
 */
class what_time_is_it_over_there extends TestCase
{
    /**
     * @test
     * @dataProvider timezones
     */
    function checking_the_time_in_a_different_timezone(string $timezone)
    {
        $clock = TimeZoneAwareClock::in(new DateTimeZone($timezone));

        $now = $clock->now();

        self::assertEquals($timezone, $now->getTimezone()->getName());
        self::assertInstanceOf(DateTimeImmutable::class, $now);
    }

    /**
     * @test
     * @dataProvider timezones
     */
    function checking_the_mutable_time_in_a_different_timezone(string $timezone)
    {
        $clock = TimeZoneAwareMutableClock::in(new DateTimeZone($timezone));

        $now = $clock->now();

        self::assertEquals($timezone, $now->getTimezone()->getName());
        self::assertInstanceOf(DateTime::class, $now);
    }

    public static function timezones(): iterable
    {
        return [
            'Europe/Amsterdam' => ['Europe/Amsterdam'],
            'Asia/Dubai' => ['Asia/Dubai'],
        ];
    }
}
