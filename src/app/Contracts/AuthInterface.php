<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Contracts\UserInterface;

interface AuthInterface
{
  public function user(): ?UserInterface;
}
