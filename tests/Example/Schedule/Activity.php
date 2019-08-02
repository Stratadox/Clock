<?php declare(strict_types=1);

namespace Your\Project;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;

class Activity
{
    private $startingTime;
    private $duration;

    public function __construct(
        DateTimeImmutable $startingTime,
        DateInterval $duration = null
    ) {
        $this->startingTime = $startingTime;
        $this->duration = $duration ?: new DateInterval('PT1H');
    }

    public function isOngoingAt(DateTimeInterface $moment): bool
    {
        return $this->startingTime < $moment
            && $moment < $this->startingTime->add($this->duration);
    }
}
