<?php

namespace SimplePhpFramework\Authentication;

interface UserServiceInterface
{
    public function findByEmail(string $email): ?AuthUserInterface;
}
