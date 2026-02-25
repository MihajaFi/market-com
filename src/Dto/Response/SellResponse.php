<?php

namespace App\Dto\Response;

class SellResponse
{
    public int $id;
    public int $merchantId;
    public int $productId;
    public float $totalSales;

    public function __construct(int $id, int $merchantId, int $productId, float $totalSales)
    {
        $this->id = $id;
        $this->merchantId = $merchantId;
        $this->productId = $productId;
        $this->totalSales = $totalSales;
    }
}