<?php

namespace App\Mapper;

use App\Entity\MerchantItem;
use App\Entity\Merchant;
use App\Dto\Request\MerchantItemRequest;
use App\Dto\Response\MerchantItemResponse;

class MerchantItemMapper
{
    public static function toEntity(MerchantItemRequest $dto, Merchant $merchant): MerchantItem
    {
        return (new MerchantItem())
            ->setMerchant($merchant);
    }

    public static function toResponse(MerchantItem $orderItem): MerchantItemResponse
    {
        return new MerchantItemResponse(
            $orderItem->getId(),
            $orderItem->getMerchant()->getId(),
            $orderItem->getMerchant()->getName()

        );
    }

    public static function update(MerchantItem $orderItem, MerchantItemRequest $dto, Merchant $merchant): MerchantItem
    {
        return $orderItem
            ->setMerchant($merchant);
    }
}