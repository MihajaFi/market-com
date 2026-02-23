<?php

namespace App\Dto\Response;

class OrderResponse
{
    public int $id;
    public string $orderDate;
    public float $totalAmount;
    public string $status;
    public string $userName;
    public string $merchantName;
    public array $items = [];
    public string $address;
    public string $phone;
    public string $paymentMethod;

    public function __construct(
        int $id,
        string $orderDate,
        float $totalAmount,
        string $status,
        string $userName,
        string $merchantName,
        array $items = [],
        string $address = '',
        string $phone = '',
        string $paymentMethod = ''
    ) {
        $this->id = $id;
        $this->orderDate = $orderDate;
        $this->totalAmount = $totalAmount;
        $this->status = $status;
        $this->userName = $userName;
        $this->merchantName = $merchantName;
        $this->items = $items;
        $this->address = $address;
        $this->phone = $phone;
        $this->paymentMethod = $paymentMethod;
    }
}