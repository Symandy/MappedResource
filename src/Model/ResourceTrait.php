<?php

declare(strict_types=1);

namespace Symandy\Component\Resource\Model;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

trait ResourceTrait
{

     #[Id]
     #[GeneratedValue]
     #[Column(type: "integer")]
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

}
