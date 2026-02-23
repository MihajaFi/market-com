<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product;
use App\Entity\Promotion;

#[ORM\Entity]
class ProductItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /* ================= PRODUCT ================= */

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_product', nullable: false)]
    private ?Product $product = null;

    /* ================= PROMOTION (MANQUAIT) ================= */

    #[ORM\ManyToOne(inversedBy: 'productItems')]
    #[ORM\JoinColumn(name: 'id_promotion', nullable: false)]
    private ?Promotion $promotion = null;

    /* ================= GETTERS ================= */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    /* ================= SETTERS ================= */

    public function setProduct(?Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;
        return $this;
    }
}