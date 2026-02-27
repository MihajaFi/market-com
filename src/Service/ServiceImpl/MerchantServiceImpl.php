<?php

namespace App\Service\ServiceImpl;

use App\Entity\User;
use App\Dto\Request\MerchantRequest;
use App\Dto\Response\MerchantResponse;
use App\Mapper\MerchantMapper;
use App\Repository\MerchantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ServiceInterface;
use App\Repository\OrderRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MerchantServiceImpl implements ServiceInterface
{
    private MerchantRepository $repository;
    private EntityManagerInterface $em;
    private MerchantMapper $mapper;
    private OrderRepository $orderRepo;
    private UserPasswordHasherInterface $passwordHasher;


    public function __construct(
        MerchantRepository $repository,
        EntityManagerInterface $em,
        MerchantMapper $mapper,
        OrderRepository $orderRepo,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->repository = $repository;
        $this->em = $em;
        $this->mapper = $mapper;
        $this->orderRepo = $orderRepo;
        $this->passwordHasher = $passwordHasher;
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

    public function findTotalSaleByMerchant(): array
    {
        $merchants = $this->repository->findAll();

        return array_map(
            fn ($m) => $this->mapper->toResponseByMerchant($m),
            $merchants
        );
    }

    public function save(object $dto): MerchantResponse
    {
        /** @var MerchantRequest $dto */
        $user = new User();
        $user->setEmail($dto->email);
        $user->setUsername($dto->email);
        $user->setRoles(['ROLE_MERCHANT']);

        $plainPassword = bin2hex(random_bytes(4));
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        $this->em->persist($user);

        $merchant = $this->mapper->toEntity($dto);

        $this->em->persist($merchant);
        $this->em->flush();

        $response = $this->mapper->toResponse($merchant);
        $response->generatedPassword = $plainPassword;

        return $response;
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

    function getMerchantByEmail(string $email): ?MerchantResponse
    {
        $merchant = $this->repository->findOneBy(['email' => $email]);

        return $merchant
            ? $this->mapper->toResponse($merchant)
            : null;
    }
}
