<?php

namespace App\Service\ServiceImpl;

use App\Dto\Request\MerchantRequest;
use App\Dto\Response\MerchantResponse;
use App\Mapper\MerchantMapper;
use App\Repository\MerchantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ServiceInterface;
use App\Repository\OrderRepository;

class MerchantServiceImpl implements ServiceInterface
{
    private MerchantRepository $repository;
    private EntityManagerInterface $em;
    private MerchantMapper $mapper;
    private OrderRepository $orderRepo;


    public function __construct(
        MerchantRepository $repository,
        EntityManagerInterface $em,
        MerchantMapper $mapper,
        OrderRepository $orderRepo
    ) {
        $this->repository = $repository;
        $this->em = $em;
        $this->mapper = $mapper;
        $this->orderRepo = $orderRepo;
    }

    public function findById(int $id): ?MerchantResponse
    {
        $merchant = $this->repository->find($id);

        return $merchant
            ? $this->mapper->toResponse($merchant)
            : null;
    }

    public function findAll(): array
    {
        $merchants = $this->repository->findAll();

        return array_map(
            fn ($m) => $this->mapper->toResponse($m),
            $merchants
        );
    }

    public function save(object $dto): MerchantResponse
    {
        /** @var MerchantRequest $dto */
        $merchant = $this->mapper->toEntity($dto);

        $this->em->persist($merchant);
        $this->em->flush();

        return $this->mapper->toResponse($merchant);
    }

    public function update(int $id, object $dto): ?MerchantResponse
    {
        /** @var MerchantRequest $dto */
        $merchant = $this->repository->find($id);
        if (!$merchant) {
            return null;
        }

        $merchant->setName($dto->name)
            ->setEmail($dto->email)
            ->setPhone($dto->phone)
            ->setCity($dto->city)
            ->setCategory($dto->category)
            ->setStatus($dto->status)
            ->setAvatarColor($dto->avatarColor);

        $this->em->flush();

        return $this->mapper->toResponse($merchant);
    }
    
    public function delete(int $id): bool
    {
        $merchant = $this->repository->find($id);
        if (!$merchant) {
            return false;
        }

        $this->em->remove($merchant);
        $this->em->flush();

        return true;
    }
}
