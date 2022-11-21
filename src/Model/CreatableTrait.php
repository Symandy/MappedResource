<?php

declare(strict_types=1);

namespace Symandy\Component\Resource\Model;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;

use function time;

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
        $this->setCreatedAt(DateTimeImmutable::createFromFormat('U', (string) time()));
    }

}
