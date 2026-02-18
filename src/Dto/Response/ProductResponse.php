<?php

namespace App\Dto\Response;

class ProductResponse
{
    public int $id;
    public string $name;
    public string $description;
    public string $category;
    public float $price;
    public int $stock = 0; 
    public ?string $image = null;
}
