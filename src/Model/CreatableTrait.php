<?php

declare(strict_types=1);

namespace Symandy\Component\Resource\Model;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;

trait CreatableTrait
{

    #[Column(name: "created_at", type: "datetime", nullable: false)]
    protected ?DateTimeInterface $createdAt = null;

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function create(): void
    {
        $this->setCreatedAt(new DateTime());
    }

}
