<?php

namespace App\Mapper;

use App\Dto\Request\SellRequest;
use App\Dto\Response\SellResponse;
use App\Entity\Sell;
use App\Entity\Merchant;
use App\Entity\Product;

class SellMapper
{
    public static function toEntity(
        SellRequest $dto,
        Merchant $merchant,
        Product $product
    ): Sell {
        $sell = new Sell(); 

        $sell->setMerchant($merchant);
        $sell->setProduct($product);
        $sell->setTotalSales($dto->getTotalSales());

        return $sell;
    }

    public static function toResponse(Sell $sell): SellResponse
    {
        return new SellResponse(
            $sell->getId(),
            $sell->getMerchant()->getId(),
            $sell->getProduct()->getId(),
            $sell->getTotalSales(),
            $sell->getSaleDate()?->format('Y-m-d H:i:s')
        );
    }
}