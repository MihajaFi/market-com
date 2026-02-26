<?php

namespace App\Dto\Response;

class SalesByMonthResponse
{
    public function __construct(
     public string $month,
     public int $ventes,
     public int $commandes,   
    )
    {
    }
}