<?php

namespace App\Mapper;

use App\Entity\Stock;
use App\Entity\Product;
use App\Dto\Request\StockRequest;
use App\Dto\Response\StockResponse;

class StockMapper
{
   
    public static function toEntity(StockRequest $dto, Product $product): Stock
    {
        $stock = new Stock();
        $stock->setQuantity($dto->quantity)
              ->setAlert($dto->alert)
              ->setProduct($product);

        return $stock;
    }

    
    public static function toResponse(Stock $stock): StockResponse
    {
        $product = $stock->getProduct();

        return new StockResponse(
            $stock->getId(),
            $stock->getQuantity(),
            $stock->getAlert(),
            $product->getName(),
            $product->getDescription(),
            $product->getPrice()
        );
    }

    
    public static function update(Stock $stock, StockRequest $dto, Product $product): Stock
    {
        $stock->setQuantity($dto->quantity)
              ->setAlert($dto->alert)
              ->setProduct($product);

        return $stock;
    }
}