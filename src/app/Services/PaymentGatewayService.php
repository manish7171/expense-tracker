<?php

declare(strict_types=1);

namespace App\Services;

class PaymentGatewayService implements PaymentGatewayServiceInterface
{
  public function charge(array $customer, float $amount, float $tax): bool
  {
    return true;
  }
}
