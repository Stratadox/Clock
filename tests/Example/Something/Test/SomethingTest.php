<?php declare(strict_types=1);

namespace Your\Project\Test;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Stratadox\Clock\UnmovingClock;
use Your\Project\SomeFactory;

class SomethingTest extends TestCase
{
    public function testCreatingSomething(): void
    {
        $testTime = new DateTimeImmutable('1-1-1960');
        $factory = new SomeFactory(
            UnmovingClock::standingStillAt($testTime)
        );

        $something = $factory->createSomething();

        $this->assertEquals($testTime, $something->creationDate());
    }
}
