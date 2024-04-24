<?php

declare(strict_types=1);

namespace App\Entities;

use App\Model;

class User extends Model
{
  public function create(string $email, string $name, bool $isActive = true): int
  {
    $stmt = $this->db->prepare('insert into users (email, full_name, is_active, created_at, updated_at)
      values (?, ?, ?, NOW(), NOW())
      ');

    $stmt->execute([$email, $name, $isActive]);

    return (int) $this->db->lastInsertId();
  }
}
