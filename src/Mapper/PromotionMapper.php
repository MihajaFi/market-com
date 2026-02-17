<?php

namespace App\Mapper;

use App\Entity\Promotion;
use App\Dto\Request\PromotionRequest;
use App\Dto\Response\PromotionResponse;
use App\Repository\ProductRepository;
use App\Repository\PromotionLoyaltyRepository;
use App\Service\ServiceImpl\ProductServiceImpl;
use App\Service\ServiceImpl\PromotionLoyaltyServiceImpl;

class PromotionMapper
{
    private ProductRepository $productRepository;
    private PromotionLoyaltyRepository $promotionLoyaltyRepository;
    private ProductServiceImpl $productService;
    private PromotionLoyaltyServiceImpl $promotionLoyaltyService;

    public function __construct(
        ProductRepository $productRepository,
        PromotionLoyaltyRepository $promotionLoyaltyRepository,
        ProductServiceImpl $productService,
        PromotionLoyaltyServiceImpl $promotionLoyaltyService
    ) {
        $this->productRepository = $productRepository;
        $this->promotionLoyaltyRepository = $promotionLoyaltyRepository;
        $this->productService = $productService;
        $this->promotionLoyaltyService = $promotionLoyaltyService;
    }

    public function toEntity(PromotionRequest $dto): Promotion
    {
        $promotion = new Promotion();

        $promotionLoyalty = $this->promotionLoyaltyRepository->find($dto->promotionLoyalty);
        $product = $this->productRepository->find($dto->product);

        return $promotion
            ->setPromotionLoyalty($promotionLoyalty)
            ->setProduct($product);
    }

    public function toResponse(Promotion $promotion): PromotionResponse
    {
        $dto = new PromotionResponse();
        $dto->id = $promotion->getId();
        $dto->promotionLoyalty = $this->promotionLoyaltyService->findById($promotion->getPromotionLoyalty()->getId());
        $dto->product = $this->productService->findById($promotion->getProduct()->getId());

        return $dto;
    }

    public function update(Promotion $promotion, PromotionRequest $dto): Promotion
    {
        $promotionLoyalty = $this->promotionLoyaltyRepository->find($dto->promotionLoyalty);
        $product = $this->productRepository->find($dto->product);

        return $promotion
            ->setPromotionLoyalty($promotionLoyalty)
            ->setProduct($product);
    }
}
