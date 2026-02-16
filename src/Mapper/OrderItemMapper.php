<?php

namespace App\Mapper;

use App\Entity\OrderItem;
use App\Entity\Product;
use App\Dto\Request\OrderItemRequest;
use App\Dto\Response\OrderItemResponse;

class OrderItemMapper
{
    public static function toEntity(OrderItemRequest $dto, Product $product): OrderItem
    {
        return (new OrderItem())
            ->setQuantity($dto->quantity)
            ->setUnitPrice($dto->unitPrice)
            ->setProduct($product);
    }

    public static function toResponse(OrderItem $orderItem): OrderItemResponse
    {
        return new OrderItemResponse(
            $orderItem->getId(),
            $orderItem->getQuantity(),
            $orderItem->getUnitPrice(),
            $orderItem->getSubTotal(),
            $orderItem->getProduct()->getName(),
            $orderItem->getProduct()->getDescription(),
            $orderItem->getProduct()->getPrice()
        );
    }

    public static function update(OrderItem $orderItem, OrderItemRequest $dto, Product $product): OrderItem
    {
        return $orderItem
            ->setQuantity($dto->quantity)
            ->setUnitPrice($dto->unitPrice)
            ->setProduct($product);
    }
}