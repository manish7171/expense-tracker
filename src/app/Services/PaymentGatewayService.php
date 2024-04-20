<?php

declare(strict_types=1);

namespace App\Services;

class PaymentGatewayService
{
  public function charge(array $customer, float $amount, float $tax): int
  {
    sleep(1);

    return mt_rand(0, 1);
  }
}
