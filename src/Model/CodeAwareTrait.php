<?php

declare(strict_types=1);

namespace Symandy\Component\Resource\Model;

use Doctrine\ORM\Mapping\Column;

trait CodeAwareTrait
{

    #[Column(type: "string", unique: true, nullable: false)]
    protected ?string $code = null;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

}
