<?php

namespace App\Service\ServiceImpl;

use App\Repository\SellRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ServiceInterface;
use App\Dto\Response\SellResponse;
use App\Mapper\SellMapper;
use App\Entity\Sell;
use App\Entity\Product;
use App\Dto\Request\SellRequest;
use App\Repository\MerchantRepository;
use App\Repository\ProductRepository;

class SellServiceImpl implements ServiceInterface
{
    private SellRepository $repository;
    private EntityManagerInterface $em;
    private MerchantRepository $merchantRepository;
    private ProductRepository $productRepository;

    public function __construct(
        SellRepository $repository,
        MerchantRepository $merchantRepository,
        ProductRepository $productRepository,
        EntityManagerInterface $em
    ) {
        $this->repository = $repository;
        $this->em = $em;
        $this->merchantRepository = $merchantRepository;
        $this->productRepository = $productRepository;
    }

    public function findById(int $id): ?SellResponse
    {
        $sell = $this->repository->find($id);
        return $sell ? SellMapper::toResponse($sell) : null;
    }

    public function findAll(): array
    {
        $sells = $this->repository->findAll();
        return array_map(fn(Sell $s) => SellMapper::toResponse($s), $sells);
    }

    public function save(object $dto): SellResponse
    {
        if (!$dto instanceof SellRequest) {
            throw new \InvalidArgumentException('DTO must be instance of SellRequest');
        }

        $merchant = $this->merchantRepository->find($dto->getMerchantId());
        $product = $this->productRepository->find($dto->getProductId());

        if (!$merchant) {
            throw new \Exception("Merchant not found");
        }
        
        if (!$product) {
            throw new \Exception("Product not found");
        }

        $sell = SellMapper::toEntity($dto, $merchant, $product);

        $this->em->persist($sell);
        $this->em->flush();

        return SellMapper::toResponse($sell);
    }

    public function update(int $id, object $dto): ?SellResponse
    {
        if (!$dto instanceof SellRequest) {
            throw new \InvalidArgumentException("SellRequest attendu");
        }

        $sell = $this->repository->find($id);

        if (!$sell) {
            return null;
        }

        
        $merchant = $this->merchantRepository->find($dto->getMerchantId());
        $product = $this->productRepository->find($dto->getProductId());

        if (!$merchant) {
            throw new \Exception("Merchant not found");
        }

        if (!$product) {
            throw new \Exception("Product not found");
        }

        SellMapper::update($sell, $dto);
        $sell->setMerchant($merchant);
        $sell->setProduct($product);

        $this->em->flush();

        return SellMapper::toResponse($sell);
    }

    public function delete(int $id): bool
    {
        $sell = $this->repository->find($id);
        if (!$sell) {
            return false;
        }

        $this->em->remove($sell);
        $this->em->flush();

        return true;
    }

    public function getMerchantNameByProductId(int $productId): ?string
    {
        $sell = $this->repository->findOneBy(['product' => $productId]);
        return $sell && $sell->getMerchant() ? $sell->getMerchant()->getName() : null;
    }

    public function createFromOrderItem(Product $product, float $amount): void
   {
    $sell = new Sell();

    $sell->setMerchant($product->getMerchant());
    $sell->setProduct($product);
    $sell->setTotalSales($amount);

    $this->em->persist($sell);
    }

    public function deleteByProduct(Product $product): void
    {
    $sells = $this->repository->findBy(['product' => $product]);

    foreach ($sells as $sell) {
        $this->em->remove($sell);
    }
    }
}