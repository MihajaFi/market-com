<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product;
use App\Entity\PromotionLoyalty;

#[ORM\Entity]
class Promotion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: PromotionLoyalty::class)]
    #[ORM\JoinColumn(name: "id_promotion_loyalty", referencedColumnName: "id", nullable: false)]
    private PromotionLoyalty $promotionLoyalty;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'id_product', referencedColumnName: 'id', nullable: false)]
    private Product $product;

    // ================= GETTERS =================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPromotionLoyalty(): PromotionLoyalty
    {
        return $this->promotionLoyalty;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    // ================= SETTERS =================

    public function setPromotionLoyalty(PromotionLoyalty $promotionLoyalty): self
    {
        $this->promotionLoyalty = $promotionLoyalty;
        return $this;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;
        return $this;
    }

}
