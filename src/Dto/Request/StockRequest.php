<?php

namespace App\Dto\Request;

class StockRequest
{
    public int $quantity;
    public string $alert;
    public int $productId; 

    public function __construct(int $quantity = 0, string $alert = '', int $productId = 0)
    {
        $this->quantity = $quantity;
        $this->alert = $alert;
        $this->productId = $productId;
    }
}