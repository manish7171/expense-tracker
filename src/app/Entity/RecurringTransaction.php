<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contracts\OwnableInterface;
use App\Entity\Traits\HasTimestamps;
use App\Enums\TransactionType;
use App\Enums\FrequencyType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table('recurring_transactions')]
#[HasLifecycleCallbacks]
class RecurringTransaction implements OwnableInterface
{
  use HasTimestamps;

  #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
  private int $id;

  #[Column]
  private string $description;

  #[Column(name: 'category_id')]
  private int $categoryId;

  #[Column(name: 'type', type: 'string', options: ['default' => TransactionType::EXPENSE])]
  private string $type;

  #[Column(name: 'last_generated', type: 'date')]
  private string $lastGenerated;

  #[Column(
    name: 'frequency',
    type: 'string',
    length: 20,
    enumType: FrequencyType::class,
    options: ['default' => 'monthly']
)]
  private FrequencyType $frequency = FrequencyType::Monthly;

  #[Column(type: Types::DECIMAL, precision: 13, scale: 3)]
  private float $amount;

  #[ManyToOne(inversedBy: 'transactions')]
  private User $user;

  #[ManyToOne(inversedBy: 'transactions')]
  private ?Category $category;


  public function __construct()
  {
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function setDescription(string $description): RecurringTransaction
  {
    $this->description = $description;

    return $this;
  }

  public function getAmount(): float
  {
    return $this->amount;
  }

  public function setAmount(float $amount): RecurringTransaction
  {
    $this->amount = $amount;

    return $this;
  }

  public function getUser(): User
  {
    return $this->user;
  }

  public function setUser(User $user): RecurringTransaction
  {
    $this->user = $user;

    return $this;
  }

  public function getCategory(): ?Category
  {
    return $this->category;
  }

  public function setCategory(?Category $category): RecurringTransaction
  {
    $this->category = $category;

    return $this;
  }

  public function getTransactionType(): string
  {
    return $this->type;
  }

  public function setTransactionType(string $transactionType): RecurringTransaction
  {
    $this->type = $transactionType;

    return $this;
  }

  public function getFrequency(): FrequencyType
  {
    return $this->frequency;
  }

  public function setFrequency(FrequencyType $frequency): RecurringTransaction
  {
    $this->frequency = $frequency;

    return $this;
  }

  public function getLastGenerated(): string
  {
    return $this->lastGenerated;
  }

  public function setLastGenerated(string $date): RecurringTransaction
  {
    $this->lastGenerated = $date; 
    return $this;
  }

  public function getCategoryId(): int
  {
    return $this->categoryId;
  }

  public function setCategoryId(int $catId): RecurringTransaction
  {
    $this->categoryId = $catId;
    return $this;
  }
}
