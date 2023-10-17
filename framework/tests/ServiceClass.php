<?php

namespace SimplePhpFramework\Tests;

class ServiceClass
{
    public function __construct(
        private readonly SocialNetworks $socialNetworks
    )
    {
    }

    public function getSocialNetwork(): SocialNetworks
    {
        return $this->socialNetworks;
    }
}
