<?php

namespace App\Mapper;

use App\Dto\Request\SellRequest;
use App\Dto\Response\SellResponse;
use App\Entity\Sell;


class SellMapper
{
    public static function toEntity(
        SellRequest $dto,
        $merchant,
        $product
    ): Sell {
        $sell = new Sell();

        $sell->setMerchant($merchant);
        $sell->setProduct($product);
        return $sell;
    }

    public static function toResponse(Sell $sell): SellResponse
    {
        return new SellResponse(
            $sell->getId(),
            $sell->getMerchant()->getId(),
            $sell->getProduct()->getId()
        );
    }
}