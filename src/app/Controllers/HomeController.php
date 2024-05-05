<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\View;
use Twig\Environment;

class HomeController
{

  public function __construct(private Environment $twig)
  {
  }

  #[Get('/')]
  public function index(): string
  {
    return $this->twig->render('dashboard.twig');
  }
}
