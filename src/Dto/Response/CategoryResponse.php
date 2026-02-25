<?php

namespace App\Dto\Response;

class CategoryResponse
{
    public int $id;
    public string $name;
    public ?string $description;
    public string $color;
    public int $productCount;
}