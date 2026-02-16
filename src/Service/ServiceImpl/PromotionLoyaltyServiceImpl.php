<?php

namespace App\Service\ServiceImpl;

use App\Dto\Request\PromotionLoyaltyRequest;
use App\Dto\Response\PromotionLoyaltyResponse;
use App\Entity\PromotionLoyalty;
use App\Mapper\PromotionLoyaltyMapper;
use App\Repository\PromotionLoyaltyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ServiceInterface;

class PromotionLoyaltyServiceImpl implements ServiceInterface
{
    private PromotionLoyaltyRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(PromotionLoyaltyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    public function findById(int $id): ?PromotionLoyaltyResponse
    {
        $PromotionLoyalty = $this->repository->find($id);
        return $PromotionLoyalty ? PromotionLoyaltyMapper::toResponse($PromotionLoyalty) : null;
    }

    public function findAll(): array
    {
        $PromotionLoyaltys = $this->repository->findAll();
        return array_map(fn(PromotionLoyalty $p) => PromotionLoyaltyMapper::toResponse($p), $PromotionLoyaltys);
    }

    public function save(object $dto): PromotionLoyaltyResponse
    {
        $PromotionLoyalty = PromotionLoyaltyMapper::toEntity($dto);
        $this->em->persist($PromotionLoyalty);
        $this->em->flush();

        return PromotionLoyaltyMapper::toResponse($PromotionLoyalty);
    }

    public function update(int $id, object $dto): ?PromotionLoyaltyResponse
    {
        $PromotionLoyalty = $this->repository->find($id);
        if (!$PromotionLoyalty) {
            return null;
        }

        $PromotionLoyalty = PromotionLoyaltyMapper::update($PromotionLoyalty, $dto);
        $this->em->flush();

        return PromotionLoyaltyMapper::toResponse($PromotionLoyalty);
    }

    public function delete(int $id): bool
    {
        $PromotionLoyalty = $this->repository->find($id);
        if (!$PromotionLoyalty) {
            return false;
        }

        $this->em->remove($PromotionLoyalty);
        $this->em->flush();

        return true;
    }
}
