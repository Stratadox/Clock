<?php declare(strict_types=1);

namespace Stratadox\Clock;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * Clock. An injectable date/time factory.
 *
 * @author Stratadox
 */
interface Clock
{
    /**
     * Indicates the current time. As a bonus, the date is also included.
     *
     * @return DateTimeInterface|DateTimeImmutable|DateTime
     */
    public function now(): DateTimeInterface;
}
