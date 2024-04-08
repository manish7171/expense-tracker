<?php

namespace App\Enums;

class Status
{
  public const PAID = "paid";
  public const PENDING = "pending";
  public const DECLINED = "declined";

  public const ALL = [
    self::PAID => "Paid",
    self::PENDING => "Pending",
    self::DECLINED => "Declined",
  ];
}
