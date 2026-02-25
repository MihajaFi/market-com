<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

class SellRequest
{
    #[Assert\NotBlank(message: 'L\'ID du marchand est requis')]
    public int $merchantId;

    #[Assert\NotBlank(message: 'L\'ID du produit est requis')]
    public int $productId;

    #[Assert\NotBlank(message: 'Le total des ventes est requis')]
    #[Assert\PositiveOrZero(message: 'Le total des ventes doit être positif ou zéro')]
    public float $totalSales;

    public function getMerchantId(): int
    {
        return $this->merchantId;
    }
    
    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getTotalSales(): float
    {
        return $this->totalSales;
    }
}