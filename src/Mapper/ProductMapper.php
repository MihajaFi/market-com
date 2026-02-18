<?php

namespace App\Mapper;

use App\Entity\Product;
use App\Dto\Request\ProductRequest;
use App\Dto\Response\ProductResponse;

class ProductMapper
{
    public static function toEntity(ProductRequest $dto, ?string $imagePath = null): Product
  {
    return (new Product())
        ->setName($dto->name)
        ->setDescription($dto->description)
        ->setCategory($dto->category)
        ->setPrice($dto->price)
        ->setImage($imagePath);
  }


    public static function toResponse(Product $product): ProductResponse
    {
        $dto = new ProductResponse();
        $dto->id = $product->getId();
        $dto->name = $product->getName();
        $dto->description = $product->getDescription();
        $dto->category = $product->getCategory();
        $dto->price = $product->getPrice();

        $totalQuantity = 0;
        foreach ($product->getStocks() as $stock) {
            $totalQuantity += $stock->getQuantity();
        }
        $dto->stock = $totalQuantity;
        $dto->image = $product->getImage();


        return $dto;
    }

    public static function update(Product $product, ProductRequest $dto): Product
    {
        return $product
            ->setName($dto->name)
            ->setDescription($dto->description)
            ->setCategory($dto->category)
            ->setPrice($dto->price);
    }
}
