<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'orders')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $orderDate = null;

    #[ORM\Column(type: 'float')]
    private ?float $totalAmount = 0;

    #[ORM\Column(length: 25)]
    private ?string $status = null;

     #[ORM\Column(type: 'text')]
    private string $address;

     #[ORM\Column(type: 'text')]
    private string $phone;

     #[ORM\Column(type: 'text')]
    private string $paymentMethod;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'id_user', nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(
        mappedBy: 'order',
        targetEntity: OrderItem::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->orderDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $date): static
    {
        $this->orderDate = $date;
        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $amount): static
    {
        $this->totalAmount = $amount;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }
    
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setOrder($this);
        }
        return $this;
    }

    public function removeItem(OrderItem $item): static
    {
        $this->items->removeElement($item);
        return $this;
    }

        public function getAddress(): string
        {
            return $this->address;
        }

        public function setAddress(string $address): static
        {
            $this->address = $address;
            return $this;
        }

        public function getPhone(): string
        {
            return $this->phone;
        }

        public function setPhone(string $phone): static
        {
            $this->phone = $phone;
            return $this;
        }

        public function getPaymentMethod(): string
        {
            return $this->paymentMethod;
        }

        public function setPaymentMethod(string $paymentMethod): static
        {
            $this->paymentMethod = $paymentMethod;
            return $this;
        }

        
}