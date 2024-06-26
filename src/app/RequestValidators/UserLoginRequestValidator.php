<?php

declare(strict_types=1);

namespace App\RequestValidators;

use App\Contracts\RequestValidatorInterface;
use App\Entity\User;
use App\Exceptions\ValidationException;
use Doctrine\ORM\EntityManager;
use Valitron\Validator;

class UserLoginRequestValidator implements RequestValidatorInterface
{

  public function validate(array $data): array
  {
    $v = new Validator($data);

    $v->rule('required', ['email', 'password']);
    $v->rule('email', 'email');

    if (!$v->validate()) {
      throw new ValidationException($v->errors());
    }

    return $data;
  }
}
