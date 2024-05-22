<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class AuthController
{
  public function __construct(private readonly Twig $twig, private readonly EntityManager $entityManager)
  {
  }

  public function loginView(Request $request, Response $response): Response
  {
    return $this->twig->render($response, 'auth/login.twig');
  }

  public function registerView(Request $request, Response $response): Response
  {
    return $this->twig->render($response, 'auth/register.twig');
  }

  public function register(Request $request, Response $response): Response
  {
    $data = $_POST;
    $data = $_POST;
    $v = new Validator($data);
    $v->rule('required', ['name', 'email', 'password', 'confirmPassword']);
    $v->rule('email', 'email');
    $v->rule('equals', 'confirmPassword', 'password')->label('Confirm password');
    $v->rule(function ($field, $value, $params, $fields) {
      return !$this->em->getRepository(User::class)->count(['email' => $value]);
    }, "email")->message("User with given email address already exists");

    if (!$v->validate()) {
      $_SESSION['errors'] = $v->errors();
      $sensitiveFields = ['password', 'confirmPassword'];
      $_SESSION['old'] = array_diff_key($_POST, array_flip($sensitiveFields));
      header("location:/register");
      //throw new ValidationException($v->errors());
    }


    $data = $request->getParsedBody();

    $user = new User();

    $user->setName($data['name']);
    $user->setEmail($data['email']);
    $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]));

    $this->entityManager->persist($user);
    $this->entityManager->flush();

    return $response;
  }
}
