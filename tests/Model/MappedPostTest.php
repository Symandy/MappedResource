<?php

declare(strict_types=1);

namespace Symandy\Tests\Component\Resource\Model;

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
use Symandy\Tests\Component\Resource\app\Post;

class MappedPostTest extends TestCase
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
    public function testPostIsPersisted(): void
    {
        $post = new Post();
        $post->create();
        $post->archive();
        $post->disable();
        $post->setCode('rsc-1');
        $post->setSlug('resource-1');

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $this->entityManager->clear();

        $repository = $this->entityManager->getRepository(Post::class);
        $posts = $repository->findAll();

        self::assertCount(1, $posts);
    }

    protected function tearDown(): void
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();
    }

}
