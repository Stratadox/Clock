<?php declare(strict_types=1);

namespace Stratadox\Clock;

use DateInterval;

/**
 * RewindableClock. An injectable- and configurable date/time factory.
 *
 * @author Stratadox
 */
interface RewindableClock extends Clock
{
    /**
     * Wouldn't we all love the ability? Sadly, rewinding time would create too
     * many paradoxes, so this method only rewinds the local clock.
     *
     * @param DateInterval $interval By how much to rewind
     * @return RewindableClock       The rewinded clock
     */
    public function rewind(DateInterval $interval): RewindableClock;

    /**
     * Fast-forwards the clock by a given interval.
     *
     * @param DateInterval $interval By how much to fast-forward
     * @return RewindableClock       The fast-forwarded clock
     */
    public function fastForward(DateInterval $interval): RewindableClock;
}
