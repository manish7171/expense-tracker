<?php

declare(strict_types=1);

namespace App\Entities;

use App\Enums\InvoiceStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('invoices')]
class Invoice
{
  #[Id]
  #[Column, GeneratedValue]
  private int $id;

  #[Column(type: Types::DECIMAL, precision: 10, scale: 2)]
  private float $amount;

  #[Column(name: 'invoice_number')]
  private string $invoiceNumber;

  #[Column]
  private InvoiceStatus $status;

  #[Column(name: 'created_at')]
  private \DateTime $createAt;

  #[Column(name: 'due_date')]
  private \DateTime $dueDate;

  #[OneToMany(targetEntity: InvoiceItem::class, mappedBy: 'invoice')]
  private Collection $items;

  public function __construct()
  {
    $this->items = new ArrayCollection();
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getAmount(): float
  {
    return $this->amount;
  }

  public function setAmount(float $amount): Invoice
  {
    $this->amount = $amount;
    return $this;
  }

  public function getInvoiceNumber(): string
  {
    return $this->invoiceNumber;
  }

  public function setInvoiceNumber(string $invoiceNumber): Invoice
  {
    $this->invoiceNumber = $invoiceNumber;
    return $this;
  }

  public function getStatus(): InvoiceStatus
  {
    return $this->status;
  }

  public function setStatus(InvoiceStatus $status): Invoice
  {
    $this->status = $status;
    return $this;
  }

  public function getCreatedAt(): \DateTime
  {
    return $this->createAt;
  }

  public function setCreatedAt(\DateTime $createdAt): Invoice
  {
    $this->createAt = $createdAt;
    return $this;
  }

  public function addItem(InvoiceItem $item): Invoice
  {
    $item->setInvoice($this);
    $this->items[] = $item;
    return $this;
  }

  public function getItems(): Collection
  {
    return $this->items;
  }
}
