<?php declare(strict_types=1);

namespace Your\Project\Test;

use DateTimeImmutable;
use Your\Project\Scheduler;
use PHPUnit\Framework\TestCase;
use Stratadox\Clock\RewindableDateTimeClock;
use Stratadox\Clock\UnmovingClock;

class SchedulerTest extends TestCase
{
    public function testWhenShouldItBeOnTheirDesk(): void
    {
        $scheduler = new Scheduler(
            RewindableDateTimeClock::using(UnmovingClock::standingStillAt(
                new DateTimeImmutable('16-5-1991')
            ))
        );

        $this->assertEquals(
            new DateTimeImmutable('15-5-1991'),
            $scheduler->whenDoIWantThisOnMyDesk()
        );
    }

    public function testTheirScheduleIsFilledForTheNextThreeHours(): void
    {
        $scheduler = new Scheduler(
            RewindableDateTimeClock::using(UnmovingClock::standingStillAt(
                new DateTimeImmutable('15:30')
            ))
        );

        $schedule = $scheduler->scheduleForTheNextThreeHours();

        $this->assertTrue($schedule->isFullAt(new DateTimeImmutable('15:31')));
        $this->assertTrue($schedule->isFullAt(new DateTimeImmutable('18:29')));
        $this->assertFalse($schedule->isFullAt(new DateTimeImmutable('18:31')));
    }
}
