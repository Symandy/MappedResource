<?php

declare(strict_types=1);

namespace Symandy\Component\Resource\Model;

use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;

trait StartableTrait
{

    #[Column(name: "starts_at", type: "datetime", nullable: true)]
    private ?DateTimeInterface $startsAt = null;

    public function getStartsAt(): ?DateTimeInterface
    {
        return $this->startsAt;
    }

    public function setStartsAt(?DateTimeInterface $startsAt): void
    {
        $this->startsAt = $startsAt;
    }

}
