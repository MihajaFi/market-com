<?php

namespace App\Entity;

use App\Repository\MerchantRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: MerchantRepository::class)]
#[ORM\Table(name: 'merchants')]
class  Merchant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $email;

    #[ORM\Column(length: 20)]
    private string $phone;

   #[ORM\Column(type: 'text')]
    private string $city;

    #[ORM\Column(type: 'text')]
    private string $category;

   #[ORM\Column(type: 'text')]
    private string $status;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $joinDate;

    #[ORM\Column(type: 'text')]
    private string $avatarColor;

    #[ORM\OneToMany(mappedBy: 'merchant', targetEntity: Order::class)]
    private Collection $orders;

    public function __construct()
    {
    $this->orders = new ArrayCollection();
    }

    public function getOrders(): Collection{return $this->orders;}

    public function getId(): ?int{return $this->id;}

    public function getName(): string {return $this->name;}
    public function setName(string $name): self{   $this->name = $name;    return $this;}

    public function getEmail(): string {return $this->email;}
    public function setEmail(string $email): self {$this->email = $email;return $this;}

    public function getPhone(): string{return $this->phone;}   
    public function setPhone(string $phone): self{$this->phone = $phone;return $this;}

    public function getCity(): string{return $this->city;}
    public function setCity(string $city): self{$this->city = $city;return $this;}

    public function getCategory(): string{ return $this->category;}
    public function setCategory(string $category): self{ $this->category = $category;  return $this;}

    public function getStatus(): string{return $this->status;}
    public function setStatus(string $status): self{$this->status = $status;return $this;}

    public function getJoinDate(): \DateTimeInterface{return $this->joinDate;}
    public function setJoinDate(\DateTimeInterface $joinDate): self{$this->joinDate = $joinDate; return $this;}

    public function getAvatarColor(): string{return $this->avatarColor;}
    public function setAvatarColor(string $avatarColor): self{$this->avatarColor = $avatarColor; return $this;}

}
