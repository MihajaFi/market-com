<?php

namespace App\Dto\Request;

class OrderAndOrderItemRequest
{
    public string $status = '';
    public int $userId = 0;
    
    /** @var OrderItemRequest[] */
    public array $items = [];
}