<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\PromotionLoyalty;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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

    #[ORM\OneToMany(
        mappedBy: 'promotion',
        targetEntity: ProductItem::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $productItems;

    /**
     * percentage | fixed
     */
    #[ORM\Column(length: 20)]
    private string $type;

    /**
     * active | expirée | planifiée
     */
    #[ORM\Column(length: 20)]
    private string $status;

    public function __construct()
    {
        $this->productItems = new ArrayCollection();
    }

    /* ================= GETTERS ================= */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPromotionLoyalty(): PromotionLoyalty
    {
        return $this->promotionLoyalty;
    }

    public function getProductItems(): Collection
    {
        return $this->productItems;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /* ================= SETTERS ================= */

    public function setPromotionLoyalty(PromotionLoyalty $promotionLoyalty): self
    {
        $this->promotionLoyalty = $promotionLoyalty;
        return $this;
    }

    public function setType(string $type): self
    {
        if (!in_array($type, ['percentage', 'fixed'], true)) {
            throw new \InvalidArgumentException('Type de promotion invalide');
        }
        $this->type = $type;
        return $this;
    }

    public function setStatus(string $status): self
    {
        if (!in_array($status, ['active', 'expirée', 'planifiée'], true)) {
            throw new \InvalidArgumentException('Statut de promotion invalide');
        }
        $this->status = $status;
        return $this;
    }

    public function addProductItem(ProductItem $item): static
    {
        if (!$this->productItems->contains($item)) {
            $this->productItems->add($item);
            $item->setPromotion($this);
        }
        return $this;
    }

    public function removeProductItem(ProductItem $item): static
    {
        $this->productItems->removeElement($item);
        return $this;
    }
}