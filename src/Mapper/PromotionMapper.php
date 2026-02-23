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

    public function toEntity(PromotionRequest $dto, $productsById): Promotion
    {
        $promotion = new Promotion();

        $promotionLoyalty = $this->promotionLoyaltyRepository->find($dto->promotionLoyalty);
        if (!$promotionLoyalty) {
            throw new \Exception("PromotionLoyalty not found: " . $dto->promotionLoyalty);
        }

        $promotion->setPromotionLoyalty($promotionLoyalty);
        $promotion->setType($dto->type);
        $promotion->setStatus($dto->status);

        foreach ($dto->productItems as $itemDto) {
            $product = $productsById[$itemDto->productId] ?? null;
            if (!$product) {
                throw new \Exception("Product not found: " . $itemDto->productId);
            }

            $productItem = ProductItemMapper::toEntity($itemDto, $product);
            $promotion->addProductItem($productItem);
        }

        return $promotion;
    }

    public function toResponse(Promotion $promotion): PromotionResponse
    {
        $productItems = $promotion->getProductItems()
            ->map(fn($item) => ProductItemMapper::toResponse($item))
            ->toArray();

        $dto = new PromotionResponse();
        $dto->id = $promotion->getId();
        $dto->promotionLoyalty = $this->promotionLoyaltyService->findById(
            $promotion->getPromotionLoyalty()->getId()
        );
        $dto->productItems = $productItems;
        $dto->type = $promotion->getType();
        $dto->status = $promotion->getStatus();

        return $dto;
    }
}