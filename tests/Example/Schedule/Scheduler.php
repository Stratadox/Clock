<?php declare(strict_types=1);

namespace Your\Project;

use Stratadox\Clock\RewindableClock;

class Scheduler
{
    private $clock;

    public function __construct(RewindableClock $clock)
    {
        $this->clock = $clock;
    }

    public function scheduleForTheNextThreeHours(): Schedule
    {
        return new Schedule(
            new Activity($this->clock->now()),
            new Activity($this->clock->fastForward(new \DateInterval('PT1H'))->now()),
            new Activity($this->clock->fastForward(new \DateInterval('PT2H'))->now())
        );
    }

    public function whenDoIWantThisOnMyDesk(): \DateTimeInterface
    {
        return $this->clock->rewind(new \DateInterval('P1D'))->now();
    }
}
