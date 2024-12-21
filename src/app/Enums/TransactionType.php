<?php

namespace App\Enums;

class TransactionType
{
  public const EXPENSE = "expense";
  public const INCOME = "income";

  public const ALL = [
    self::EXPENSE => "Expense",
    self::INCOME => "Income",
  ];
}
