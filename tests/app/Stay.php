<?php

declare(strict_types=1);

namespace Symandy\Tests\Component\Resource\app;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Symandy\Component\Resource\Model\PeriodAwareTrait;
use Symandy\Component\Resource\Model\ResourceTrait;

#[Entity]
#[Table(name: "stays")]
class Stay implements StayInterface
{

    use ResourceTrait;
    use PeriodAwareTrait;

}
