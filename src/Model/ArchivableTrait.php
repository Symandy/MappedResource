<?php

declare(strict_types=1);

namespace Symandy\Component\Resource\Model;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;

use function time;

trait ArchivableTrait
{

    #[Column(name: "archived_at", type: "datetime", nullable: true)]
    protected ?DateTimeInterface $archivedAt = null;

    public function getArchivedAt(): ?DateTimeInterface
    {
        return $this->archivedAt;
    }

    public function setArchivedAt(?DateTimeInterface $archivedAt): void
    {
        $this->archivedAt = $archivedAt;
    }

    public function archive(): void
    {
        $this->setArchivedAt(DateTimeImmutable::createFromFormat('U', (string) time()));
    }

    public function restore(): void
    {
        $this->setArchivedAt(null);
    }

}
