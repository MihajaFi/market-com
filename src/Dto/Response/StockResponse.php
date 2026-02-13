<?php

namespace App\Dto\Response;

class StockResponse
{
    public int $id;
    public int $quantity;
    public string $alert;
    public string $productName;
    public string $description;
    public float $price;

    public function __construct(
        int $id,
        int $quantity,
        string $alert,
        string $productName,
        string $description,
        float $price
    ) {
        $this->id = $id;
        $this->quantity = $quantity;
        $this->alert = $alert;
        $this->productName = $productName;
        $this->description = $description;
        $this->price = $price;
    }
}