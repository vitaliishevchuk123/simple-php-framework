<?php

namespace App\Console\Commands;

use SimplePhpFramework\Console\CommandInterface;

class TestExampleCommand implements CommandInterface
{
    private string $name = 'test';

    public function execute(array $parameters = []): int
    {
        dump($parameters, $this->name);

        return 0;
    }
}
