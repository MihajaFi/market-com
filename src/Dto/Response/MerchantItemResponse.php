<?php

namespace App\Dto\Response;

class MerchantItemRequest
{
    public int $id;
    public string $merchantId;
    public string $name;

    public function __construct(
        int $id,
        int $merchantId,
        float $name,
    ) {
        $this->id = $id;
        $this->merchantId = $merchantId;
        $this->name = $name;
    }
}
