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
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/orders')]
class OrderController extends AbstractController
{
    private OrderServiceImpl $service;
    private SerializerInterface $serializer;
    private OrderRepository $orderRepository;

    public function __construct(
    OrderServiceImpl $service,
    SerializerInterface $serializer,
    OrderRepository $orderRepository
    ) {
    $this->service = $service;
    $this->serializer = $serializer;
    $this->orderRepository = $orderRepository;
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
        $orderRequest->address = $data['address'] ?? '';
        $orderRequest->phone = $data['phone'] ?? '';
        $orderRequest->paymentMethod = $data['paymentMethod'] ?? '';

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
    #[Route('/me', name: 'order_my', methods: ['GET'])]
    public function myOrders(): Response
    {
    $user = $this->getUser(); // l'utilisateur connecté
    if (!$user) {
        return $this->json(['message' => 'Utilisateur non connecté'], 401);
    }

    $orders = $this->service->findByUser($user->getId());
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
        $orderRequest->address = $data['address'] ?? '';
        $orderRequest->phone = $data['phone'] ?? '';
        $orderRequest->paymentMethod = $data['paymentMethod'] ?? '';

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

    #[Route('/{id}/status', name: 'order_update_status', methods: ['PATCH'])]
    public function updateStatus(int $id, Request $request): Response
    {
    try {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['status'])) {
            return $this->json(['message' => 'Status is required'], 400);
        }

        $orderEntity = $this->orderRepository->find($id);

        if (!$orderEntity) {
            return $this->json(['message' => 'Order not found'], 404);
        }

        $orderRequest = new OrderAndOrderItemRequest();
        $orderRequest->status = $data['status'];
        $orderRequest->userId = $orderEntity->getUser()->getId();
        $orderRequest->address = $orderEntity->getAddress();
        $orderRequest->phone = $orderEntity->getPhone();
        $orderRequest->paymentMethod = $orderEntity->getPaymentMethod();

        $orderRequest->items = [];

        foreach ($orderEntity->getItems() as $item) {
            $itemRequest = new OrderItemRequest();
            $itemRequest->productId = $item->getProduct()->getId();
            $itemRequest->quantity = $item->getQuantity();
            $itemRequest->unitPrice = $item->getUnitPrice();

            $orderRequest->items[] = $itemRequest;
        }

        $order = $this->service->update($id, $orderRequest);

        return $this->json($order);

    } catch (\Exception $e) {
        return $this->json(['message' => $e->getMessage()], 500);
    }
    }

    #[Route('/recent/date', name: 'order_recent', methods: ['GET'])]
    public function recentOrders(): JsonResponse
    {
    $orders = $this->service->getRecentOrdersResponse(5); 
    return $this->json($orders);
    }

    #[Route('/recent/merchant/{merchantId}', name: 'order_recent_by_merchant', methods: ['GET'])]
    public function recentOrdersByMerchant(int $merchantId): JsonResponse
    {
        $orders = $this->service->getRecentOrdersByMerchant($merchantId, 5);
        return $this->json($orders);
    }
    
}