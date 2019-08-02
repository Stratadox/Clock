# Clock
Factory for creating datetime objects.

[![Build Status](https://travis-ci.org/Stratadox/Clock.svg?branch=master)](https://travis-ci.org/Stratadox/Clock)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/Clock/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/Clock?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/Clock/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/Clock/?branch=master)

## What
It's really as simple as the name suggests: this is a clock, used to indicate 
what time it is.

Since it produces [DateTime](https://www.php.net/datetimeinterface) objects, 
this clock is somewhat special in the sense that it can also read the date.

## Why
- As soon as you use "unadulterated" datetime objects in your code, any test 
  you've written for it immediately risks being flaky, because if there's a tiny 
  bit of time between `new DateTime` and your assertion, the test fails.
- Instantiating a `new DateTime` or `new DateTimeImmutable` in client 
  code, is a static invocation. This introduces coupling and reduces testability.
  This obviously goes double for `date_create()` and the like.
- It's a lot more natural to get the time from a clock than to instantiate a new 
  instant each time you want to know how late it is.

## Installing
Install with `composer require stratadox/clock`

## Examples
### Clock example
In a service that needs to know the time:
```php
<?php
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

class Something
{
    private $creationDate;

    public function __construct(\DateTimeInterface $creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function creationDate(): \DateTimeInterface
    {
        return $this->creationDate;
    }
}
```

### Rewindable clock example
In a service that needs to rewind or fast-forward the clock:
```php
<?php
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
```

### Choosing a clock
The default implementation is the `DateTimeClock`. It produces a new 
`DateTimeImmutable` object whenever `now` is called.

If the datetime object needs to be passed into something that has a `DateTime` 
type hint, or otherwise relies on mutable datetime objects, it is preferable to 
solve that issue. For example by replacing the `DateTime` hint with 
`DateTimeImmutable`, or passing along a `RewindableClock`.
In cases where that is not an option, the `DateTimeMutableClock` clock can be 
used instead.

To prevent the clock from ticking while other code is running, your tests can 
instantiate and inject an `UnmovingClock`.

In a service definition:
```php
<?php
use Stratadox\Clock\Clock;
use Stratadox\Clock\DateTimeClock;

$container->set(Clock::class, function () {
    return DateTimeClock::create();
});
```

In a unit test:
```php
<?php
use Your\Project\SomeFactory;
use PHPUnit\Framework\TestCase;
use Stratadox\Clock\UnmovingClock;

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
```

When the clock needs to be able to rewind or fast-forward, use the 
`RewindableDateTimeClock` implementation.

In a service definition:
```php
<?php
use Stratadox\Clock\RewindableClock;
use Stratadox\Clock\RewindableDateTimeClock;

$container->set(RewindableClock::class, function () {
    return RewindableDateTimeClock::create();
});
```

In a unit test:
```php
<?php
use Your\Project\Scheduler;
use PHPUnit\Framework\TestCase;
use Stratadox\Clock\RewindableDateTimeClock;
use Stratadox\Clock\UnmovingClock;

class SchedulerTest extends TestCase
{
    public function testWhenItShouldBeOnTheirDesk(): void
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
}
```
