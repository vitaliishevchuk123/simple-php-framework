<?php

namespace SimplePhpFramework\Console;

use Psr\Container\ContainerInterface;

class Application
{
    public function __construct(
        private ContainerInterface $container
    )
    {
    }

    public function run(): int
    {
        // 1. Отримуємо ім'я команди

        $argv = $_SERVER['argv'];
        $commandName = $argv[1] ?? null;

        // 2. Повертаємо виняток, якщо ім'я команди не вказано

        if (!$commandName) {
            throw new ConsoleException('Invalid console command');
        }

        // 3. Використовуємо ім'я команди для отримання класу об'єкта команди з контейнера

        /** @var CommandInterface $command */
        $command = $this->container->get("console:$commandName");

        // 4. Отримуємо опції та аргументи

        $args = array_slice ($argv, 2);
         $options = $this->parseOptions($args);

         // 5. Виконати команду, повертаючи код статусу

         $status = $command->execute($options);

         return $status;
     }

    private function parseOptions(array $args): array
    {
        $options = [];

        foreach ($args as $arg) {
            if (str_starts_with($arg, '--')) {
                $option = explode('=', substr($arg, 2));
                $options[$option[0]] = $option[1] ?? true;
            }
        }

        return $options;
    }
}