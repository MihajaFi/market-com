<?php

namespace App\Dto\Request;

class OrderRequest
{
    public string $status;
    public int $userId;
    public array $items;

    public function __construct(string $status = '', int $userId = 0, array $items = [])
    {
        $this->status = $status;
        $this->userId = $userId;
        $this->items = $items;
    }
}