<?php

namespace App\Mapper;

use App\Entity\PromotionLoyalty;
use App\Dto\Request\PromotionLoyaltyRequest;
use App\Dto\Response\PromotionLoyaltyResponse;

class PromotionLoyaltyMapper
{
    public static function toEntity(PromotionLoyaltyRequest $dto): PromotionLoyalty
    {
        return (new PromotionLoyalty())
            ->setPromotionType($dto->promotion_type)
            ->setValue($dto->value)
            ->setStartDate($dto->start_date)
            ->setEndDate($dto->end_date)
            ->setConditions($dto->conditions);
    }

    public static function toResponse(PromotionLoyalty $promotionLoyalty): PromotionLoyaltyResponse
    {
        $dto = new PromotionLoyaltyResponse();
        $dto->id = $promotionLoyalty->getId();
        $dto->promotion_type = $promotionLoyalty->getPromotionType();
        $dto->value = $promotionLoyalty->getValue();
        $dto->start_date = $promotionLoyalty->getStartDate();
        $dto->end_date = $promotionLoyalty->getEndDate();
        $dto->conditions = $promotionLoyalty->getConditions(); 

        return $dto;
    }

    public static function update(PromotionLoyalty $promotionLoyalty, PromotionLoyaltyRequest $dto): PromotionLoyalty
    {
        return $promotionLoyalty
            ->setPromotionType($dto->promotion_type)
            ->setValue($dto->value)
            ->setStartDate($dto->start_date)
            ->setEndDate($dto->end_date)
            ->setConditions($dto->conditions);
    }
}
