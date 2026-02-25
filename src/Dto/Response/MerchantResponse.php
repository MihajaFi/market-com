<?php

namespace App\Dto\Response;

class MerchantResponse
{
    public int $id;
    public string $name;
    public string $email;
    public string $phone;
    public string $city;
    public string $status;
    public string $joinDate;
    public int $totalSales;
    public string $avatarColor;
}
