<?php

namespace App\Dto\Response;

class DashboardResponse
{
    public function __construct(
        public readonly int $totalSell,
        public readonly int $totalProducts,
        public readonly int $totalMerchant,
        public readonly int $orderPending
    ) {}
}