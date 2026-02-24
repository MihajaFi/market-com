<?php

namespace App\Dto\Response;

use DateTime;

class PromotionLoyaltyResponse
{
    public int $id;
    public string $promotion_type;
    public string $description;
    public float $value;
    public string $start_date;
    public string $end_date;
    public string $conditions;
}
