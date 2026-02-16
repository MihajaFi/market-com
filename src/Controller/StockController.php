<?php

namespace App\Controller;

use App\Dto\Request\StockRequest;
use App\Service\ServiceImpl\StockServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

    #[Route('/api/stocks')]
class StockController extends AbstractController
    {
    private StockServiceImpl $service;
    private SerializerInterface $serializer;

    public function __construct(StockServiceImpl $service, SerializerInterface $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    // GET all stocks
    #[Route('', name: 'stock_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $stocks = $this->service->findAll();
        return new JsonResponse($stocks);
    }

    // GET stock by ID
    #[Route('/{id}', name: 'stock_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $stock = $this->service->findById($id);
        if (!$stock) {
            return new JsonResponse(['message' => 'Stock not found'], 404);
        }
        return new JsonResponse($stock);
    }

    // POST create stock
    #[Route('', name: 'stock_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $stockRequest = $this->serializer->deserialize(
            $request->getContent(),
            StockRequest::class,
            'json'
        );

        if ($stockRequest->quantity < 0 || empty($stockRequest->alert) || $stockRequest->productId <= 0) {
            return new JsonResponse(['message' => 'Invalid stock data'], 400);
        }

        try {
            $stock = $this->service->save($stockRequest);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 404);
        }

        return new JsonResponse($stock, 201);
    }

    // PUT update stock
    #[Route('/{id}', name: 'stock_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $stockRequest = $this->serializer->deserialize(
            $request->getContent(),
            StockRequest::class,
            'json'
        );

        if ($stockRequest->quantity < 0 || empty($stockRequest->alert) || $stockRequest->productId <= 0) {
            return new JsonResponse(['message' => 'Invalid stock data'], 400);
        }

        try {
            $stock = $this->service->update($id, $stockRequest);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 404);
        }

        if (!$stock) {
            return new JsonResponse(['message' => 'Stock not found'], 404);
        }

        return new JsonResponse($stock);
    }

    // DELETE stock
    #[Route('/{id}', name: 'stock_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $deleted = $this->service->delete($id);
        if (!$deleted) {
            return new JsonResponse(['message' => 'Stock not found'], 404);
        }
        return new JsonResponse(['message' => 'Stock deleted']);
    }
}