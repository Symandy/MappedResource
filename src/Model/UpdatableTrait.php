<?php

declare(strict_types=1);

namespace Symandy\Component\Resource\Model;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;

use function time;

trait UpdatableTrait
{

    #[Column(name: "updated_at", type: "datetime", nullable: false)]
    protected ?DateTimeInterface $updatedAt = null;

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function update(): void
    {
        $this->setUpdatedAt(DateTimeImmutable::createFromFormat('U', (string) time()));
    }

}
