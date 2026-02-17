<?php

namespace App\Service\ServiceImpl;

use App\Dto\Request\PromotionRequest;
use App\Dto\Response\PromotionResponse;
use App\Entity\Promotion;
use App\Mapper\PromotionMapper;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ServiceInterface;

class PromotionServiceImpl implements ServiceInterface
{
    private PromotionRepository $repository;
    private EntityManagerInterface $em;
    private PromotionMapper $mapper;

    public function __construct(
        PromotionRepository $repository,
        EntityManagerInterface $em,
        PromotionMapper $mapper
    ) {
        $this->repository = $repository;
        $this->em = $em;
        $this->mapper = $mapper;
    }

    public function findById(int $id): ?PromotionResponse
    {
        $promotion = $this->repository->find($id);

        return $promotion
            ? $this->mapper->toResponse($promotion)
            : null;
    }

    public function findAll(): array
    {
        $promotions = $this->repository->findAll();

        return array_map(
            fn (Promotion $p) => $this->mapper->toResponse($p),
            $promotions
        );
    }

    public function save(object $dto): PromotionResponse
    {
        /** @var PromotionRequest $dto */
        $promotion = $this->mapper->toEntity($dto);

        $this->em->persist($promotion);
        $this->em->flush();

        return $this->mapper->toResponse($promotion);
    }

    public function update(int $id, object $dto): ?PromotionResponse
    {
        /** @var PromotionRequest $dto */
        $promotion = $this->repository->find($id);

        if (!$promotion) {
            return null;
        }

        $this->mapper->update($promotion, $dto);
        $this->em->flush();

        return $this->mapper->toResponse($promotion);
    }

    public function delete(int $id): bool
    {
        $promotion = $this->repository->find($id);

        if (!$promotion) {
            return false;
        }

        $this->em->remove($promotion);
        $this->em->flush();

        return true;
    }
}
