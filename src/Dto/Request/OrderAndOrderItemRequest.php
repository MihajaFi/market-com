<?php

namespace App\Dto\Request;

class OrderAndOrderItemRequest
{
    public string $status = '';
    public int $userId = 0;
    public int $merchantId = 0;
    /** @var OrderItemRequest[] */
    public array $items = [];
    public string $address = '';
    public string $phone = '';
    public string $paymentMethod = '';

}