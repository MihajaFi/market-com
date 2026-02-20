<?php

namespace App\Mapper;

use App\Entity\Order;
use App\Dto\Request\OrderAndOrderItemRequest;
use App\Dto\Response\OrderResponse;
use App\Mapper\OrderItemMapper;

class OrderMapper
{
    public static function toResponse(Order $order): OrderResponse
    {
        $items = $order->getItems()->map(fn($item) => OrderItemMapper::toResponse($item))->toArray();

        return new OrderResponse(
            $order->getId(),
            $order->getOrderDate()->format('Y-m-d'),
            $order->getTotalAmount(),
            $order->getStatus(),
            $order->getUser()->getUsername(),
            $items,
            $order->getAddress(),
            $order->getPhone(),
            $order->getPaymentMethod()
        );
    }

public static function toEntity(OrderAndOrderItemRequest $dto, $user, $productsById)
{
    $order = new \App\Entity\Order();
    $order->setUser($user);
    $order->setStatus($dto->status);
    $order->setAddress($dto->address);
    $order->setPhone($dto->phone);
    $order->setPaymentMethod($dto->paymentMethod);

    foreach ($dto->items as $itemDto) {
        $product = $productsById[$itemDto->productId] ?? null;
        if (!$product) throw new \Exception("Product not found: " . $itemDto->productId);

        $orderItem = OrderItemMapper::toEntity($itemDto, $product);
        $order->addItem($orderItem);
    }

   
    $total = array_reduce($order->getItems()->toArray(), fn($carry, $i) => $carry + $i->getSubTotal(), 0);
    $order->setTotalAmount($total);

    return $order;
}
}