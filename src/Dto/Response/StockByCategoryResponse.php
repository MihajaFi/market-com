<?php

namespace App\Dto\Response;

class StockByCategoryResponse 
{
    public string $category;
    public int $stock;

    public function __construct(string $category, int $stock)
    {
        $this->category = $category;
        $this->stock = $stock;
    }
}