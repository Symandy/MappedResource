<?php

declare(strict_types=1);

namespace Symandy\Tests\Component\Resource\Model;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use PHPUnit\Framework\TestCase;
use Symandy\Tests\Component\Resource\app\Post;

final class VersionedPostTest extends TestCase
{

    /**
     * @throws ToolsException
     * @throws ORMException
     */
    protected function setUp(): void
    {
        $entityManager = $this->createEntityManager();

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();

        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->createSchema($metadata);
    }

    /**
     * @throws ORMException
     */
    private function createEntityManager(): EntityManagerInterface
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

        return EntityManager::create($dbParams, $config);
    }


    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testPostIsPersistedAndVersioned(): void
    {
        $entityManager1 = $this->createEntityManager();
        $entityManager2 = $this->createEntityManager();

        $post1 = new Post();
        $post1->create();
        $post1->setCode('rsc-1');
        $post1->setSlug('resource-1');

        $entityManager1->persist($post1);
        $entityManager1->flush();
        $entityManager1->clear();

        $post1 = $entityManager1->find(Post::class, 1, LockMode::OPTIMISTIC, 1);
        self::assertSame(1, $post1->getVersion());

        $post2 = $entityManager2->find(Post::class, 1, LockMode::OPTIMISTIC, 1);
        self::assertSame(1, $post2->getVersion());

        $post2->enable();

        $entityManager2->flush();
        $entityManager2->refresh($post2);
        self::assertSame(2, $post2->getVersion());

        $post1->enable();

        $this->expectException(OptimisticLockException::class);
        $entityManager1->flush();
    }

    /**
     * @throws ORMException
     */
    protected function tearDown(): void
    {
        $schemaTool = new SchemaTool($this->createEntityManager());
        $schemaTool->dropDatabase();
    }

}
