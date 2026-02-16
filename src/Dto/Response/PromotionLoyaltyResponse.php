<?php

namespace App\Dto\Response;

use DateTime;

class PromotionLoyaltyResponse
{
    public int $id;
    public string $promotion_type;
    public string $description;
    public float $value;
    public DateTime $start_date;
    public DateTime $end_date;
    public string $conditions;
}
