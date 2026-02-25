<?php

namespace App\Mapper;

use App\Entity\Product;
use App\Dto\Request\ProductRequest;
use App\Dto\Response\ProductResponse;
use App\Entity\Merchant;

class ProductMapper
{
    public static function toEntity(
        ProductRequest $dto,
        Merchant $merchant,
        ?string $imagePath = null
    ): Product {
        return (new Product())
            ->setName($dto->name)
            ->setDescription($dto->description)
            ->setCategory($dto->category)
            ->setPrice($dto->price)
            ->setImage($imagePath)
            ->setMerchant($merchant);
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

        $dto->merchant = $product->getMerchant()?->getName();

        return $dto;
    }

    public static function update(Product $product, ProductRequest $dto, Merchant $merchant): Product
    {
        return $product
            ->setName($dto->name)
            ->setDescription($dto->description)
            ->setCategory($dto->category)
            ->setPrice($dto->price)
            ->setMerchant($merchant);
    }
}