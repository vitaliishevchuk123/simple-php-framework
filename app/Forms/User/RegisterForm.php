<?php

namespace App\Forms\User;

use App\Entities\User;
use App\Services\UserService;

class RegisterForm
{
    private ?string $name;
    private string $email;
    private string $password;
    private string $passwordConfirmation;
    private array $validationErrors = [];

    public function setFields(string $email, string $password, string $passwordConfirmation, string $name = null): void
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
    }

    public function __construct(
        private UserService $userService
    )
    {
    }

    public function save(): User
    {
        $user = User::create(
            $this->email,
            password_hash($this->password, PASSWORD_DEFAULT),
            new \DateTimeImmutable(),
            $this->name
        );

        $user = $this->userService->save($user);

        return $user;
    }

    public function validate(): void
    {
        $this->validationErrors = [];

        if (!empty($this->name) && strlen($this->name) > 50) {
            $this->validationErrors[] = 'Максимальна довжина імені 50 символів';
        }

        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->validationErrors[] = 'Неправильний формат електронної пошти';
        }

        if (empty($this->password) || strlen($this->password) < 8) {
            $this->validationErrors[] = 'Мінімальна довжина пароля 8 символів';
        }

        if ($this->password !== $this->passwordConfirmation) {
            $this->validationErrors[] = 'Паролі не збігаються';
        }
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    public function hasValidationErrors(): bool
    {
        return !empty($this->validationErrors);
    }
}