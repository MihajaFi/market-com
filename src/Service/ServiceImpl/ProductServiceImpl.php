<?php

namespace App\Service\ServiceImpl;

use App\Dto\Response\ProductResponse;
use App\Entity\Product;
use App\Mapper\ProductMapper;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Dto\Request\ProductRequest;

class ProductServiceImpl 
{
    private ProductRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(ProductRepository $repository, EntityManagerInterface $em, string $projectDir)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->projectDir = $projectDir;
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

     public function register(object $dto, ?string $imagePath = null): ProductResponse
    {
        /** @var ProductRequest $dto */
        $product = ProductMapper::toEntity($dto, $imagePath);

        $this->em->persist($product);
        $this->em->flush();

        return ProductMapper::toResponse($product);
    }

    public function update(int $id, object $dto, ?string $imagePath = null): ?ProductResponse
    {
        /** @var ProductRequest $dto */

        $product = $this->repository->find($id);
        if (!$product) {
            return null;
        }

        $oldImage = $product->getImage();

        ProductMapper::update($product, $dto, $imagePath);
        if ($imagePath && $oldImage && $oldImage !== $imagePath) {
            $this->deleteFile($oldImage);
        }

        $this->em->flush();

        return ProductMapper::toResponse($product);
    }

    public function delete(int $id): bool
    {
        $product = $this->repository->find($id);
        if (!$product) {
            return false;
        }
   if ($product->getImage()) {
            $this->deleteFile($product->getImage());
        }

        $this->em->remove($product);
        $this->em->flush();

        return true;
    }

    private function deleteFile(string $path): void
    {
        $fullPath = $this->projectDir . '/public' . $path;

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
