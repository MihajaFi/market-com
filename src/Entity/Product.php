<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Stock;
use App\Entity\Sell;

#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\ManyToOne(targetEntity: Merchant::class)]
    #[ORM\JoinColumn(name: 'id_merchant', nullable: false)]
    private ?Merchant $merchant = null;

   #[ORM\ManyToOne(targetEntity: Category::class)]
   #[ORM\JoinColumn(name: 'category_id', nullable: false)]
   private ?Category $category = null;

    #[ORM\Column(type: 'float')]
    private float $price;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Stock::class)]
    private Collection $stocks;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Sell::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $sells;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
        $this->sells = new ArrayCollection();
    }

    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function getSells(): Collection
    {
    return $this->sells;
} 
    public function addStock(Stock $stock): self
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks->add($stock);
            $stock->setProduct($this);
        }
        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stocks->removeElement($stock)) {
            if ($stock->getProduct() === $this) {
                $stock->setProduct(null);
            }
        }
        return $this;
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getDescription(): string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }

    public function getPrice(): float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }

    public function getImage(): ?string{return $this->image;}
    public function setImage(?string $image): self{$this->image = $image;return $this;}

    public function getCategory(): ?Category
    {
    return $this->category;
    }

    public function setCategory(Category $category): self
    {
    $this->category = $category;
    return $this;
    }
    
    public function getMerchant(): ?Merchant
    {
    return $this->merchant;
    }

    public function setMerchant(?Merchant $merchant): self
     {
    $this->merchant = $merchant;
    return $this;
    }
}
