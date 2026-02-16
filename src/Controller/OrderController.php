<?php

namespace App\Controller;

use App\Dto\Request\OrderAndOrderItemRequest;
use App\Dto\Request\OrderItemRequest;
use App\Service\ServiceImpl\OrderServiceImpl;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/orders')]
class OrderController extends AbstractController
{
    private OrderServiceImpl $service;
    private SerializerInterface $serializer;

    public function __construct(OrderServiceImpl $service, SerializerInterface $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    #[Route('', name: 'order_create', methods: ['POST'])]
public function create(Request $request): Response
{
    try {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['message' => 'Invalid JSON: ' . json_last_error_msg()], 400);
        }

        $orderRequest = new OrderAndOrderItemRequest();
        $orderRequest->status = $data['status'] ?? '';
        $orderRequest->userId = isset($data['userId']) ? (int) $data['userId'] : 0;

        $orderRequest->items = [];
        foreach ($data['items'] ?? [] as $itemData) {
            $item = new OrderItemRequest();
            $item->productId = (int) ($itemData['productId'] ?? 0);
            $item->quantity = (int) ($itemData['quantity'] ?? 0);
            $item->unitPrice = (float) ($itemData['unitPrice'] ?? 0.0);
            $orderRequest->items[] = $item;
        }

        if (empty($orderRequest->status) || $orderRequest->userId === 0 || empty($orderRequest->items)) {
            return $this->json([
                'message' => 'Invalid order data',
                'received' => $data
            ], 400);
        }

        $order = $this->service->save($orderRequest);
        return $this->json($order, 201);

    } catch (\Exception $e) {
        return $this->json([
            'message' => 'Error',
            'error' => $e->getMessage()
        ], 400);
    }
}

    // GET all orders
    #[Route('', name: 'order_list', methods: ['GET'])]
    public function list(): Response
    {
        $orders = $this->service->findAll();
        $json = $this->serializer->serialize($orders, 'json');
        return new Response($json, 200, ['Content-Type' => 'application/json']);
    }

    // GET order by ID
    #[Route('/{id}', name: 'order_get', methods: ['GET'])]
    public function get(int $id): Response
    {
        $order = $this->service->findById($id);

        if (!$order) {
            return new Response(
                $this->serializer->serialize(['message' => 'Order not found'], 'json'),
                404,
                ['Content-Type' => 'application/json']
            );
        }

        $json = $this->serializer->serialize($order, 'json');
        return new Response($json, 200, ['Content-Type' => 'application/json']);
    }

    // PUT update 
    #[Route('/{id}', name: 'order_update', methods: ['PUT'])]
    public function update(int $id, Request $request): Response
    {
    try {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['message' => 'Invalid JSON: ' . json_last_error_msg()], 400);
        }

        $orderRequest = new OrderAndOrderItemRequest();
        $orderRequest->status = $data['status'] ?? '';
        $orderRequest->userId = isset($data['userId']) ? (int) $data['userId'] : 0;

        $orderRequest->items = [];
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $itemData) {
                $item = new OrderItemRequest();
                $item->productId = isset($itemData['productId']) ? (int) $itemData['productId'] : 0;
                $item->quantity = isset($itemData['quantity']) ? (int) $itemData['quantity'] : 0;
                $item->unitPrice = isset($itemData['unitPrice']) ? (float) $itemData['unitPrice'] : 0.0;
                $orderRequest->items[] = $item;
            }
        }

        if (empty($orderRequest->status) || $orderRequest->userId === 0 || empty($orderRequest->items)) {
            return $this->json(['message' => 'Invalid order data', 'received' => $data], 400);
        }

        $order = $this->service->update($id, $orderRequest);

        if (!$order) {
            return $this->json(['message' => 'Order not found'], 404);
        }

        return $this->json($order, 200);

    } catch (\Exception $e) {
        return $this->json(['message' => $e->getMessage()], 400);
    }
    }

    // DELETE order
    #[Route('/{id}', name: 'order_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return new Response(
                $this->serializer->serialize(['message' => 'Order not found'], 'json'),
                404,
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            $this->serializer->serialize(['message' => 'Order deleted'], 'json'),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}