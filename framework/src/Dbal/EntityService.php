<?php

namespace SimplePhpFramework\Dbal;

use Doctrine\DBAL\Connection;
use SimplePhpFramework\Dbal\Event\EntityPersist;
use SimplePhpFramework\Event\EventDispatcher;

class EntityService
{
    public function __construct(
        private readonly Connection $connection,
        private readonly EventDispatcher $eventDispatcher
    ) {
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function save(Entity $entity): int
    {
        $entityId = $this->connection->lastInsertId();

        $entity->setId($entityId);

        $this->eventDispatcher->dispatch(new EntityPersist($entity));

        return $entityId;
    }
}
