<?php

declare(strict_types=1);

namespace Symandy\Component\Resource\Model;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Version;

trait VersionedTrait
{

    #[Column(type: "integer")]
    #[Version]
    protected ?int $version = 1;

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(?int $version): void
    {
        $this->version = $version;
    }

}
