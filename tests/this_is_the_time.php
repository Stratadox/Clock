<?php declare(strict_types=1);

namespace Stratadox\Clock\Test;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Stratadox\Clock\UnmovingClock;

/**
 * @testdox this is the time
 */
class this_is_the_time extends TestCase
{
    /** @test */
    function this_is_definitely_the_time()
    {
        $clock = UnmovingClock::standingStillAt(new DateTimeImmutable('30-4-1945'));

        $now = $clock->now();

        $this->assertEquals(new DateTimeImmutable('30-4-1945'), $now);
        $this->assertInstanceOf(DateTimeImmutable::class, $now);
    }
}
