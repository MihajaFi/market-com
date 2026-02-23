<?php

namespace App\Dto\Response;
use App\Dto\Response\PromotionLoyaltyResponse;

class PromotionResponse
{
    public int $id;
    public PromotionLoyaltyResponse $promotionLoyalty;
    public array $productItems = [];
    public string $type;
    public string $status;
}
