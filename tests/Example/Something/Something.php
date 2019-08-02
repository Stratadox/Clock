<?php declare(strict_types=1);

namespace Your\Project;

use DateTimeInterface;

class Something
{
    private $creationDate;

    public function __construct(DateTimeInterface $creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function creationDate(): DateTimeInterface
    {
        return $this->creationDate;
    }
}
