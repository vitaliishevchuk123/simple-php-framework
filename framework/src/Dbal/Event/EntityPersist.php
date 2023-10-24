<?php

namespace SimplePhpFramework\Dbal\Event;

use SimplePhpFramework\Dbal\Entity;
use SimplePhpFramework\Event\Event;

class EntityPersist extends Event
{
    public function __construct(
        private Entity $entity
    ) {
    }

    public function getEntity(): Entity
    {
        return $this->entity;
    }
}
