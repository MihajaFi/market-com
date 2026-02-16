<?php

namespace App\Service\ServiceImpl;

use App\Dto\Response\StockResponse;
use App\Entity\Stock;
use App\Mapper\StockMapper;
use App\Repository\StockRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ServiceInterface;

class StockServiceImpl implements ServiceInterface
{
    private StockRepository $repository;
    private ProductRepository $productRepository;
    private EntityManagerInterface $em;

    public function __construct(
        StockRepository $repository,
        ProductRepository $productRepository,
        EntityManagerInterface $em
    ) {
        $this->repository = $repository;
        $this->productRepository = $productRepository;
        $this->em = $em;
    }

    public function findById(int $id): ?StockResponse
    {
        $stock = $this->repository->find($id);
        return $stock ? StockMapper::toResponse($stock) : null;
    }

    public function findAll(): array
    {
        $stocks = $this->repository->findAll();
        return array_map(fn(Stock $s) => StockMapper::toResponse($s), $stocks);
    }

    public function save(object $dto): StockResponse
    {
        
        $product = $this->productRepository->find($dto->productId);
        if (!$product) {
            throw new \Exception("Product not found with ID " . $dto->productId);
        }

        $stock = StockMapper::toEntity($dto, $product);
        $this->em->persist($stock);
        $this->em->flush();

        return StockMapper::toResponse($stock);
    }

    public function update(int $id, object $dto): ?StockResponse
    {
        $stock = $this->repository->find($id);
        if (!$stock) {
            return null;
        }

        $product = $this->productRepository->find($dto->productId);
        if (!$product) {
            throw new \Exception("Product not found with ID " . $dto->productId);
        }

        $stock = StockMapper::update($stock, $dto, $product);
        $this->em->flush();

        return StockMapper::toResponse($stock);
    }

    public function delete(int $id): bool
    {
        $stock = $this->repository->find($id);
        if (!$stock) {
            return false;
        }

        $this->em->remove($stock);
        $this->em->flush();

        return true;
    }
}