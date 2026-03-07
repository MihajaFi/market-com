<?php

namespace App\Dto\Response;

class OrderCurrentResponse
{
    public int $id;
    public string $userName;
    public float $totalAmount;
    public string $orderDate;
    public string $status;

    public function __construct(
        int $id,
        string $userName,
        float $totalAmount,
        string $orderDate,
        string $status
    ) {
        $this->id = $id;
        $this->userName = $userName;
        $this->totalAmount = $totalAmount;
        $this->orderDate = $orderDate;
        $this->status = $status;
    }
}