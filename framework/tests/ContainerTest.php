<?php

namespace SimplePhpFramework\Tests;

use PHPUnit\Framework\TestCase;
use SimplePhpFramework\Container\Container;

class ContainerTest extends TestCase
{
    public function test_getting_service_from_container()
    {
        $container = new Container();

        $container->add('service-class', ServiceClass::class);

        $this->assertInstanceOf(ServiceClass::class, $container->get('service-class'));
    }
}
