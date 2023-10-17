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
            if (!class_exists($id)) {
                throw new ContainerException("Service $id not found");
            }

            $concrete = $id;
        }

        $this->services[$id] = $concrete;
    }

    public function get(string $id)
    {
        if (!$this->has($id)) {
            if (!class_exists($id)) {
                throw new ContainerException("Service $id could not be resolved");
            }

            $this->add($id);
        }

        $instance = $this->resolve($this->services[$id]);

        return $instance;
    }

    private function resolve($class)
    {
        // 1. Створити екземпляр класу Reflection

        $reflectionClass = new \ReflectionClass($class);

        // 2. Використовувати Reflection спроби отримати конструктор класу

        $constructor = $reflectionClass->getConstructor();

        // 3. Якщо немає конструктора, просто створити екземпляр

        if (is_null($constructor)) {
            return $reflectionClass->newInstance();
        }

        // 4. Отримати параметри конструктора

        $constructorParams = $constructor->getParameters();

        // 5. Отримати залежності

        $classDependencies = $this->resolveClassDependencies($constructorParams);

        // 6. Створити екземпляр із залежностями

        $instance = $reflectionClass->newInstanceArgs($classDependencies);

        // 7. Повернути об'єкт

        return $instance;
    }

    private function resolveClassDependencies(array $constructorParams): array
    {
        // 1. Ініціалізувати порожній перелік залежностей

        $classDependencies = [];

        // 2. Спробувати знайти та створити екземпляр

        /** @var \ReflectionParameter $constructorParam */
        foreach ($constructorParams as $constructorParam) {
            // Отримати тип параметра

            $serviceType = $constructorParam->getType();

            // Спробувати створити екземпляр, використовуючи $serviceType

            $service = $this->get($serviceType->getName());

            // Додати сервіс у classDependencies

            $classDependencies[] = $service;
        }

        // 3. Повернути масив classDependencies

        return $classDependencies;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }
}