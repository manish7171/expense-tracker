<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;

class Invoice extends Model
{

  public function create(float $amount, int $userId): int
  {
    $stmt = $this->db->prepare('insert into invoices (amount, user_id)
      values (?, ?)
      ');

    $stmt->execute([$amount, $userId]);

    return (int) $this->db->lastInsertId();
  }

  public function find(int $invoiceId): array
  {
    $stmt = $this->db->prepare(
      'SELECT invoices.id, amount, full_name 
      FROM invoices 
      LEFT JOIN users on users.id = user_id 
      WHERE invoices.id = ?'
    );

    $stmt->execute([$invoiceId]);

    $invoice = $stmt->fetch();

    return $invoice ?? [];
  }
}
