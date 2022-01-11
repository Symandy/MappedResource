<?php

declare(strict_types=1);

namespace Symandy\Component\Resource\Model;

use Doctrine\ORM\Mapping\Column;

trait SlugAwareTrait
{

    #[Column(type: "string", unique: true, nullable: false)]
    protected ?string $slug = null;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

}
