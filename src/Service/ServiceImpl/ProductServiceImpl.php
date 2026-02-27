<?php

namespace App\Service\ServiceImpl;

use App\Dto\Response\ProductResponse;
use App\Entity\Product;
use App\Mapper\ProductMapper;
use App\Repository\ProductRepository;
use App\Repository\ProductItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Dto\Request\ProductRequest;
use App\Repository\MerchantRepository;
use App\Repository\CategoryRepository;
use App\Dto\Response\PromotionLoyaltyResponse;
use App\Mapper\PromotionLoyaltyMapper;

class ProductServiceImpl 
{
    private ProductRepository $repository;
    private EntityManagerInterface $em;
    private MerchantRepository $merchantRepo;
    private CategoryRepository $categoryRepo;
    private ProductItemRepository $productItemRepo;

    public function __construct(
    ProductRepository $repository,
    MerchantRepository $merchantRepo,
    EntityManagerInterface $em,
    CategoryRepository $categoryRepo,
    ProductItemRepository $productItemRepo,
    string $projectDir

) {
    $this->repository = $repository;
    $this->merchantRepo = $merchantRepo;
    $this->em = $em;
    $this->categoryRepo = $categoryRepo;
    $this->productItemRepo = $productItemRepo;
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

    $merchant = $this->merchantRepo->find($dto->merchantId);

    if (!$merchant) {
        throw new \Exception("Merchant not found");
    }

    $category = $this->categoryRepo->find($dto->categoryId);
    if (!$category) {
    throw new \Exception("Category not found");
    }

    $product = ProductMapper::toEntity($dto, $merchant, $category, $imagePath);

    $this->em->persist($product);
    $this->em->flush();

    return ProductMapper::toResponse($product);
    }

   public function update(int $id, object $dto, ?string $imagePath = null): ?ProductResponse
    {
    /** @var ProductRequest $dto */
    $product = $this->repository->find($id);
    if (!$product) return null;

    $merchant = $this->merchantRepo->find($dto->merchantId);
    if (!$merchant) throw new \Exception("Merchant not found");

    $category = $this->categoryRepo->find($dto->categoryId);
    if (!$category) throw new \Exception("Category not found"); 

    $oldImage = $product->getImage();

    ProductMapper::update($product, $dto, $merchant, $category);

    if ($imagePath) {
        $product->setImage($imagePath);
    }

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

    public function findByMerchantId(int $merchantId): array
    {
        $products = $this->repository->findBy(['merchant' => $merchantId]);
        return array_map(fn(Product $p) => ProductMapper::toResponse($p), $products);
    }

    public function findPromotionsLoyalityByProductId(int $productId) : ?PromotionLoyaltyResponse
    {

    $loyalty = $this->productItemRepo->findLoyaltyByProductId($productId);
    return PromotionLoyaltyMapper::toResponse($loyalty);

    } 
}
