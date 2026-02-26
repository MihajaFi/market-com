<?php

namespace App\Dto\Response;

class salesByMonth
{
    public function __construct(
     public string $month,
     public string $ventes,
     public string $commandes,   
    )
    {
    }
}