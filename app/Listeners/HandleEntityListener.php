<?php

namespace App\Listeners;

use SimplePhpFramework\Dbal\Event\EntityPersist;

class HandleEntityListener
{
    public function __invoke(EntityPersist $event): void
    {
//         dd($event->getEntity());
    }
}
