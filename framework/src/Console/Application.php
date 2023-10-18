<?php

namespace SimplePhpFramework\Console;

use Psr\Container\ContainerInterface;

class Application
{
    public function __construct(
        private ContainerInterface $container
    ) {
    }

    public function run(): int
    {
        // 1. Получаем имя команды

        $argv = $_SERVER['argv'];
        $commandName = $argv[1] ?? null;

        // 2. Возвращаем исключение, если имя команда не указана

        if (! $commandName) {
            throw new ConsoleException('Invalid console command');
        }

        // 3. Используем имя команды для получения объекта класса команды из контейнера

        /** @var CommandInterface $command */
        $command = $this->container->get("console:$commandName");

        // 4. Получаем опции и аргументы

        $args = array_slice($argv, 2);
        $options = $this->parseOptions($args);

        // 5. Выполнить команду, возвращая код статуса

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
