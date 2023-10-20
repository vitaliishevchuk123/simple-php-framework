<?php

namespace App\Entities;

class User
{
    public function __construct(
        private ?int $id,
        private ?string $name,
        private string $email,
        private string $password,
        private \DateTimeImmutable $createdAt
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public static function create(string $email, string $password, \DateTimeImmutable $createdAt = null, string $name = null, int $id = null): static
    {
        return new static($id, $name, $email, $password, $createdAt ?? new \DateTimeImmutable());
    }
}
