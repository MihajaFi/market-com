<?php

namespace App\Mapper;

use App\Entity\ProductItem;
use App\Entity\Product;
use App\Dto\Request\ProductItemRequest;
use App\Dto\Response\ProductItemResponse;

class ProductItemMapper
{
   public static function toEntity(ProductItemRequest $dto, Product $product): ProductItem
    {
        return (new ProductItem())
            ->setProduct($product);
    }
    public static function toResponse(ProductItem $productItem): ProductItemResponse
    {
    $response = new ProductItemResponse();

    $response->id = $productItem->getId();
    $response->product = ProductMapper::toResponse($productItem->getProduct());

    return $response;
    }

     public static function update(ProductItem $productItem, ProductItemRequest $dto, Product $product): ProductItem
    {
        return $productItem
            ->setProduct($product);
    }
}
