<?php

declare(strict_types=1);

namespace Symandy\Tests\Component\Resource\Model;

use DateTime;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\SchemaValidator;
use Doctrine\ORM\Tools\ToolsException;
use Doctrine\Persistence\Mapping\MappingException;
use PHPUnit\Framework\TestCase;
use Symandy\Tests\Component\Resource\app\Stay;

final class MappedStayTest extends TestCase
{

    private EntityManagerInterface $entityManager;

    /**
     * @throws ToolsException|ORMException
     */
    protected function setUp(): void
    {
        $paths = [__DIR__ . '/../app'];
        $dbParams = [
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/../var/mapped-resource.sqlite'
        ];

        $config = new Configuration();
        $config->setMetadataDriverImpl(new AttributeDriver($paths));
        $config->setProxyDir(__DIR__ . '/../var/cache/Proxies');
        $config->setProxyNamespace('Proxies');

        $this->entityManager = EntityManager::create($dbParams, $config);

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();

        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->createSchema($metadata);
    }


    public function testMappingIsCorrect(): void
    {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $validator = new SchemaValidator($this->entityManager);

        self::assertNotEmpty($metadata);
        self::assertEmpty($validator->validateMapping());
        self::assertTrue($validator->schemaInSyncWithMetadata());
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws MappingException
     */
    public function testStayIsPersisted(): void
    {
        $repository = $this->entityManager->getRepository(Stay::class);
        $posts = $repository->findAll();

        self::assertCount(0, $posts);

        $stay = new Stay();
        $stay->setStartsAt(new DateTime());
        $stay->setEndsAt((new DateTime())->modify('+2 weeks'));

        $this->entityManager->persist($stay);
        $this->entityManager->flush();

        $this->entityManager->clear();

        $repository = $this->entityManager->getRepository(Stay::class);
        $posts = $repository->findAll();

        self::assertCount(1, $posts);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testStayIsPersistedWithData(): void
    {
        $startsAt = new DateTime();
        $endsAt = (new DateTime())->modify('+2 weeks');

        $stay = new Stay();
        $stay->setStartsAt($startsAt);
        $stay->setEndsAt($endsAt);

        $this->entityManager->persist($stay);
        $this->entityManager->flush();

        $this->entityManager->refresh($stay);

        self::assertSame(1, $stay->getId());
        self::assertSame($startsAt->format('Y-m-d H:i:s'), $stay->getStartsAt()->format('Y-m-d H:i:s'));
        self::assertSame($endsAt->format('Y-m-d H:i:s'), $stay->getEndsAt()->format('Y-m-d H:i:s'));
    }

    protected function tearDown(): void
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();
    }

}
