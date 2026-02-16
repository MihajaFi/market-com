<?php

namespace App\Controller;

use App\Dto\Request\ProductRequest;
use App\Service\ServiceImpl\ProductServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

    #[Route('/api/products')]
class ProductController extends AbstractController
    {
    private ProductServiceImpl $service;
    private SerializerInterface $serializer;

    public function __construct(ProductServiceImpl $service, SerializerInterface $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    // GET all
    #[Route('', name: 'product_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $products = $this->service->findAll();
        return new JsonResponse($products); 
    }

    // GET by ID
    #[Route('/{id}', name: 'product_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $product = $this->service->findById($id);
        if (!$product) {
            return new JsonResponse(['message' => 'Product not found'], 404);
        }
        return new JsonResponse($product);
    }

    // POST create
    #[Route('', name: 'product_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $productRequest = $this->serializer->deserialize(
            $request->getContent(),
            ProductRequest::class,
            'json'
        );
        if (empty($productRequest->name) || empty($productRequest->description) || $productRequest->price <= 0) {
            return new JsonResponse(['message' => 'Invalid product data'], 400);
        }
        $product = $this->service->save($productRequest);
        return new JsonResponse($product, 201);
    }

    // PUT update
    #[Route('/{id}', name: 'product_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $productRequest = $this->serializer->deserialize(
            $request->getContent(),
            ProductRequest::class,
            'json'
        );
        if (empty($productRequest->name) || empty($productRequest->description) || $productRequest->price <= 0) {
            return new JsonResponse(['message' => 'Invalid product data'], 400);
        }
        $product = $this->service->update($id, $productRequest);
        if (!$product) {
            return new JsonResponse(['message' => 'Product not found'], 404);
        }
        return new JsonResponse($product);
    }

    // DELETE
    #[Route('/{id}', name: 'product_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $deleted = $this->service->delete($id);
        if (!$deleted) {
            return new JsonResponse(['message' => 'Product not found'], 404);
        }
        return new JsonResponse(['message' => 'Product deleted']);
    }
}
