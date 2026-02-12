<?php

namespace App\Service\ServiceImpl;

use App\Dto\Request\ProductRequest;
use App\Dto\Response\ProductResponse;
use App\Entity\Product;
use App\Mapper\ProductMapper;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ServiceInterface;

class ProductServiceImpl implements ServiceInterface
{
    private ProductRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(ProductRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    public function findById(int $id): ?ProductResponse
    {
        $product = $this->repository->find($id);
        return $product ? ProductMapper::toResponse($product) : null;
    }

    public function findAll(): array
    {
        $products = $this->repository->findAll();
        return array_map(fn(Product $p) => ProductMapper::toResponse($p), $products);
    }

    public function save(object $dto): ProductResponse
    {
        $product = ProductMapper::toEntity($dto);
        $this->em->persist($product);
        $this->em->flush();

        return ProductMapper::toResponse($product);
    }

    public function update(int $id, object $dto): ?ProductResponse
    {
        $product = $this->repository->find($id);
        if (!$product) {
            return null;
        }

        $product = ProductMapper::update($product, $dto);
        $this->em->flush();

        return ProductMapper::toResponse($product);
    }

    public function delete(int $id): bool
    {
        $product = $this->repository->find($id);
        if (!$product) {
            return false;
        }

        $this->em->remove($product);
        $this->em->flush();

        return true;
    }
}
