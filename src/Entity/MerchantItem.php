<?php

namespace App\Entity;

use App\Repository\MerchantItemRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: MerchantItemRepository::class)]
class MerchantItem {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_merchant', nullable: false)]
    private ?Merchant $merchant = null;
    
    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(name: 'id_order', nullable: false)]
    private ?Order $order = null;


     public function getId(): ?int
    {
        return $this->id;
    }

    public function getMerchant(): ?Merchant
    {
        return $this->merchant;
    }

    public function setMerchant(Merchant $merchant): static
    {
        $this->merchant = $merchant;
        return $this;
    }

     public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): static
    {
        $this->order = $order;
        return $this;
    }

}