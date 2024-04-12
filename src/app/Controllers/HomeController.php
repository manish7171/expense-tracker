<?php

declare(strict_types=1);

namespace App\Controllers;

use App\View;
use PDO;

class HomeController
{
  public static function index(): View
  {
    $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'];
    $username = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];
    try {
      $db = new PDO($dsn, $username, $password, [
        //PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
      ]);
      $query = 'Select * from users';
      foreach ($db->query($query) as $user) {
        echo '<pre>';
        var_dump($user);
        echo '</pre>';
      }
    } catch (\PDOException $e) {
      throw new \PDOException($e->getMessage(), $e->getCode());
    }
    var_dump($db);
    return View::make('index', ["foo" => "bar"]);
  }

  public function upload()
  {
    echo "<pre>";
    var_dump($_FILES);
    echo "</pre>";
    $filePath = STORAGE_PATH . '/' . $_FILES['receipt']['name'];
    move_uploaded_file($_FILES['receipt']['tmp_name'], $filePath);
    echo "<pre>";
    var_dump(pathinfo($filePath));
    echo "</pre>";
  }
}
