<?php declare(strict_types=1);

namespace Your\Project;

use Stratadox\Clock\Clock;

class SomeFactory
{
    private $clock;

    public function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    public function createSomething(): Something
    {
        return new Something($this->clock->now());
    }
}
