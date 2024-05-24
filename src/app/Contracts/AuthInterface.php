<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Contracts\UserInterface;
use App\DTO\RegisterUserData;

interface AuthInterface
{
  public function user(): ?UserInterface;

  public function attemptLogin(array $credentials): bool;

  public function checkCredential(UserInterface $user, array $credentials): bool;

  public function logout(): void;

  public function register(RegisterUserData $data): UserInterface;

  public function login(UserInterface $user): void;
}
