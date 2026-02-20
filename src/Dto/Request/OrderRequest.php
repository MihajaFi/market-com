<?php

namespace App\Dto\Request;

class OrderRequest
{
    public string $status;
    public int $userId;
    public array $items;
    public string $address;
    public string $phone;
    public string $paymentMethod;

    public function __construct(string $status = '', int $userId = 0, array $items = [], string $address = '', string $phone = '', string $paymentMethod = '')
    {
        $this->status = $status;
        $this->userId = $userId;
        $this->items = $items;
        $this->address = $address;
        $this->phone = $phone;
        $this->paymentMethod = $paymentMethod;
    }
}