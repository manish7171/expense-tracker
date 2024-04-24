<?php

declare(strict_types=1);

namespace App\Controllers;

use App\View;
use App\Attributes\Get;
use App\Attributes\Post;

class UserController
{
  #[Get('/users/create')]
  public function create(): View
  {
    return View::make('users/registration');
  }

  #[Post('/users/register')]
  public function register(): void
  {
    $name = $_POST['name'];
    $email = $_POST['email'];

    var_dump($_POST);
  }
}
