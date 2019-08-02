<?php declare(strict_types=1);

namespace Your\Project;

use DateTimeInterface;

class Schedule
{
    private $activities;

    public function __construct(Activity ...$activities)
    {
        $this->activities = $activities;
    }

    public function isFullAt(DateTimeInterface $moment): bool
    {
        foreach ($this->activities as $activity) {
            if ($activity->isOngoingAt($moment)) {
                return true;
            }
        }
        return false;
    }
}
