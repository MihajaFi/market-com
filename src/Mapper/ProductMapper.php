<?php

namespace App\Mapper;

use App\Entity\Product;
use App\Dto\Request\ProductRequest;
use App\Dto\Response\ProductResponse;

class ProductMapper
{
    public static function toEntity(ProductRequest $dto): Product
    {
        return (new Product())
            ->setName($dto->name)
            ->setDescription($dto->description)
            ->setPrice($dto->price);
    }

    public static function toResponse(Product $product): ProductResponse
    {
        $dto = new ProductResponse();
        $dto->id = $product->getId();
        $dto->name = $product->getName();
        $dto->description = $product->getDescription();
        $dto->price = $product->getPrice();

        $totalQuantity = 0;
        foreach ($product->getStocks() as $stock) {
            $totalQuantity += $stock->getQuantity();
        }
        $dto->stock = $totalQuantity;

        return $dto;
    }

    public static function update(Product $product, ProductRequest $dto): Product
    {
        return $product
            ->setName($dto->name)
            ->setDescription($dto->description)
            ->setPrice($dto->price);
    }
}
