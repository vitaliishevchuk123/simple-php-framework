<?php

namespace SimplePhpFramework\Container;

use Psr\Container\ContainerInterface;
use SimplePhpFramework\Container\Exceptions\ContainerException;

class Container implements ContainerInterface
{
    private array $services = [];

    public function add(string $id, string|object $concrete = null)
    {
        if (is_null($concrete)) {
            if (! class_exists($id)) {
                throw new ContainerException("Service $id not found");
            }

            $concrete = $id;
        }

        $this->services[$id] = $concrete;
    }

    public function get(string $id)
    {
        return new $this->services[$id];
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }
}
