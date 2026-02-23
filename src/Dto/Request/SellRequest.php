<?php

namespace App\Dto\Request;

class SellRequest
{
    #[Assert\NotBlank(message: 'L\'ID du marchand est requis')]
    public int $merchantId;

    #[Assert\NotBlank(message: 'L\'ID du produit est requis')]
    public int $productId;

     public function getMerchantId(): int
    {
        return $this->merchantId;
    }
    
    public function getProductId(): int
    {
        return $this->productId;
    }

}