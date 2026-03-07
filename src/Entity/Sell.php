<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Sell
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_merchant', nullable: false)]
    private ?Merchant $merchant = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_product', nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(name: 'total_sales', type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private ?float $totalSales = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $saleDate = null;

    public function __construct()
    {

       $this->saleDate = new \DateTime('now', new \DateTimeZone(date_default_timezone_get()));
    
    }

    public function getId(): ?int { return $this->id; }

    public function getMerchant(): ?Merchant { return $this->merchant; }
    public function setMerchant(?Merchant $merchant): self { $this->merchant = $merchant; return $this; }

    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): self { $this->product = $product; return $this; }

    public function getTotalSales(): ?float { return $this->totalSales; }
    public function setTotalSales(float $totalSales): self { $this->totalSales = $totalSales; return $this; }

    public function getSaleDate(): ?\DateTimeInterface { return $this->saleDate; }
    public function setSaleDate(\DateTimeInterface $date): static { $this->saleDate = $date; return $this; }
}