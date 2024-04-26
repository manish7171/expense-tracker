<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\Contracts\EmailValidationInterface;

class CurlController
{
  public function __construct(private EmailValidationInterface $emailValidationService)
  {
  }

  #[Get('/curl')]
  public function index(): void
  {
    $email = "manish7171@gmail.com";
    $result = $this->emailValidationService->verify($email);
    echo '<pre>';
    print_r($result);
  }
}
