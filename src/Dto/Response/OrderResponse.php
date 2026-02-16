<?php

namespace App\Dto\Response;

class OrderResponse
{
    public int $id;
    public string $orderDate;
    public float $totalAmount;
    public string $status;
    public string $userName;
    public string $userEmail;
    public array $items = [];

    public function __construct(
        int $id,
        string $orderDate,
        float $totalAmount,
        string $status,
        string $userName,
        string $userEmail,
        array $items = []
    ) {
        $this->id = $id;
        $this->orderDate = $orderDate;
        $this->totalAmount = $totalAmount;
        $this->status = $status;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->items = $items;
    }
}