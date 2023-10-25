<?php

namespace SimplePhpFramework\Dbal;

use Doctrine\DBAL\Logging\DebugStack;

class Debuger
{
    public function __construct(private DebugStack $debugStack)
    {
    }

    public function render(): void
    {
        dump($this->debugStack);
    }
}