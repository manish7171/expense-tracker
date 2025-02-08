<?php

declare(strict_types=1);

namespace App\RequestValidators;

use App\Contracts\RequestValidatorInterface;
use App\Enums\TransactionType;
use App\Exceptions\ValidationException;
use Valitron\Validator;

class UpdateCategoryRequestValidator implements RequestValidatorInterface
{
  public function validate(array $data): array
  {
    $v = new Validator($data);

    $v->rule('required', 'name')->message('Required field');
    $v->rule('lengthMax', 'name', 50);
    $v->rule('required', 'category-type');
    $v->rule('in', 'category-type', [TransactionType::EXPENSE, TransactionType::INCOME]);

    if (!$v->validate()) {
      throw new ValidationException($v->errors());
    }

    return $data;
  }
}
