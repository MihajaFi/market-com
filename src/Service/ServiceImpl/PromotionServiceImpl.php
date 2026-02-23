<?php

namespace App\Service\ServiceImpl;

use App\Dto\Request\PromotionRequest;
use App\Dto\Response\PromotionResponse;
use App\Entity\Promotion;
use App\Mapper\PromotionMapper;
use App\Repository\PromotionRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ServiceInterface;

class PromotionServiceImpl implements ServiceInterface
{
    private PromotionRepository $repository;
    private ProductRepository $productRepository;
    private EntityManagerInterface $em;
    private PromotionMapper $mapper;

    public function __construct(
        PromotionRepository $repository,
        ProductRepository $productRepository,
        EntityManagerInterface $em,
        PromotionMapper $mapper
    ) {
        $this->repository = $repository;
        $this->productRepository = $productRepository;
        $this->em = $em;
        $this->mapper = $mapper;
    }

    /* ================= FIND ================= */

    public function findById(int $id): ?PromotionResponse
    {
        $promotion = $this->repository->find($id);
        return $promotion ? $this->mapper->toResponse($promotion) : null;
    }

    public function findAll(): array
    {
        return array_map(
            fn(Promotion $p) => $this->mapper->toResponse($p),
            $this->repository->findAll()
        );
    }

    /* ================= SAVE ================= */

    public function save(object $dto): PromotionResponse
    {
        /** @var PromotionRequest $dto */

        $productsById = $this->loadProductsById($dto);

        $promotion = $this->mapper->toEntity($dto, $productsById);

        $this->em->persist($promotion);
        $this->em->flush();

        return $this->mapper->toResponse($promotion);
    }

    /* ================= UPDATE ================= */

    public function update(int $id, object $dto): ?PromotionResponse
    {
        /** @var PromotionRequest $dto */

        $promotion = $this->repository->find($id);
        if (!$promotion) return null;

        $productsById = $this->loadProductsById($dto);

        $updated = $this->mapper->toEntity($dto, $productsById);

        $promotion->setPromotionLoyalty($updated->getPromotionLoyalty());

        foreach ($promotion->getProductItems() as $item) {
            $promotion->removeProductItem($item);
        }

        foreach ($updated->getProductItems() as $item) {
            $promotion->addProductItem($item);
        }

        $this->em->flush();

        return $this->mapper->toResponse($promotion);
    }


    public function delete(int $id): bool
    {
        $promotion = $this->repository->find($id);
        if (!$promotion) return false;

        $this->em->remove($promotion);
        $this->em->flush();

        return true;
    }
    
    private function loadProductsById(PromotionRequest $dto): array
    {
        $ids = array_map(
            fn($item) => $item->productId,
            $dto->productItems
        );

        $products = $this->productRepository->findBy(['id' => $ids]);

        $productsById = [];
        foreach ($products as $product) {
            $productsById[$product->getId()] = $product;
        }

        return $productsById;
    }
}