<?php

declare(strict_types = 1);

namespace App\PaymentGateway\Paddle;

use App\Enums\Status;

class Transaction
{
  private float $amount = 5.0;

  private string $status;

  public static int $count = 0;

  public function __construct()
  {
    self::$count++;
    $this->setStatus(Status::PENDING);
  }

  public function setStatus(string $status): self
  {
    if (! isset(Status::ALL[$status])) {
      throw new \InvalidArgumentException("Invalid Status");
    }

    $this->status = $status;

    return $this;
  }
}
