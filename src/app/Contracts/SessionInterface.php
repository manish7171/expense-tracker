<?php

declare(strict_types=1);

namespace App\Contracts;

interface SessionInterface
{
  public function start(): void;

  public function save(): void;

  public function isActive(): bool;

  public function forget(): void;

  public function regenerate(): bool;

  public function put(string $key, mixed $value): void;

  public function get(string $key, mixed $default = null): mixed;

  public function has(string $key): bool;

  public function flash(string $key, mixed $value): void;

  public function getFlash(string $key, mixed $default = null): mixed;
}
