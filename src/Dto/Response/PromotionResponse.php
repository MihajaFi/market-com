<?php

namespace App\Dto\Response;
use App\Dto\Response\ProductResponse;
use App\Dto\Response\PromotionLoyaltyResponse;

class PromotionResponse
{
    public int $id;
    public PromotionLoyaltyResponse $promotionLoyalty;
    public ProductResponse $product;

}
