<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product;

#[ORM\Entity]
class Stock
{
    #[ORM\Id, ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    #[ORM\Column(type: 'string', length: 250)]
    private string $alert;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'id_product', referencedColumnName: 'id', nullable: false)]
    private Product $product;

    public function getId(): int
    {
        return $this->id;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getAlert(): string
    {
        return $this->alert;
    }

    public function setAlert(string $alert): self
    {
        $this->alert = $alert;
        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;
        return $this;
    }
}