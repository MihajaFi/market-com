<?php

namespace App\Dto\Response;

class OrderItemResponse
{
    public int $id;
    public int $quantity;
    public float $unitPrice;
    public float $subTotal;
    public string $productName;
    public string $productDescription;
    public float $productPrice;

    public function __construct(
        int $id,
        int $quantity,
        float $unitPrice,
        float $subTotal,
        string $productName,
        string $productDescription,
        float $productPrice
    ) {
        $this->id = $id;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
        $this->subTotal = $subTotal;
        $this->productName = $productName;
        $this->productDescription = $productDescription;
        $this->productPrice = $productPrice;
    }
}