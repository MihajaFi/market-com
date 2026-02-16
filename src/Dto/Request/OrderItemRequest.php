<?php

namespace App\Dto\Request;

class OrderItemRequest
{
    public int $productId = 0;
    public int $quantity = 0;
    public float $unitPrice = 0.0;
}