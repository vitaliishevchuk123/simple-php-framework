<?php

namespace SimplePhpFramework\Console;

use Psr\Container\ContainerInterface;

class Kernel
{
    public function __construct(
        private ContainerInterface $container,
        private Application        $application
    )
    {
    }

    public function handle(): int
    {
        // 1. Реєстрація команд за допомогою контейнера
        $this->registerCommands(__DIR__ . '/Commands', $this->container->get('framework-commands-namespace'));
        $this->registerCommands(dirname(__DIR__, 3) . '/app/Console/Commands', $this->container->get('app-commands-namespace'));

        // 2. Запуск команди

        $status = $this->application->run();

        // 3. Повертаємо код

        return $status;
    }

    private function registerCommands($commandFilesPath, $namespace): void
    {
        // 1. Отримати всі файли з папки Commands

        $commandFiles = new \DirectoryIterator($commandFilesPath);

        // 2. Пройти всім файлам

        foreach ($commandFiles as $commandFile) {
            if (!$commandFile->isFile()) {
                continue;
            }

            // 3. Дістати ім'я класу команди

            $command = $namespace . pathinfo($commandFile, PATHINFO_FILENAME);

            // 4. Якщо це підклас CommandInterface

            if (is_subclass_of($command, CommandInterface::class)) {
                // -> Додати в контейнер (використовуючи ім'я команди як ID)

                $name = (new \ReflectionClass($command))
                    ->getProperty('name')
                    ->getDefaultValue();

                $this->container->add("console:$name", $command);
            }
        }
    }
}