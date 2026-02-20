<?php

namespace App\Service\ServiceImpl;

use App\Dto\Request\OrderAndOrderItemRequest;
use App\Dto\Response\OrderResponse;
use App\Entity\Order;
use App\Mapper\OrderMapper;
use App\Mapper\OrderItemMapper;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ServiceInterface;

class OrderServiceImpl implements ServiceInterface
{
    private OrderRepository $orderRepo;
    private UserRepository $userRepo;
    private ProductRepository $productRepo;
    private EntityManagerInterface $em;

    public function __construct(
        OrderRepository $orderRepo,
        UserRepository $userRepo,
        ProductRepository $productRepo,
        EntityManagerInterface $em
    ) {
        $this->orderRepo = $orderRepo;
        $this->userRepo = $userRepo;
        $this->productRepo = $productRepo;
        $this->em = $em;
    }

    public function findById(int $id): ?OrderResponse
    {
        $order = $this->orderRepo->find($id);
        return $order ? OrderMapper::toResponse($order) : null;
    }

    public function findAll(): array
    {
        $orders = $this->orderRepo->findAll();
        return array_map(fn(Order $o) => OrderMapper::toResponse($o), $orders);
    }
    

    public function save(object $dto): OrderResponse
    {
    if (!$dto instanceof OrderAndOrderItemRequest) {
        throw new \InvalidArgumentException("DTO must be OrderAndOrderItemRequest");
    }

    $user = $this->userRepo->find($dto->userId);
    if (!$user) throw new \Exception("User not found");

    $productIds = array_map(fn($i) => $i->productId, $dto->items);
    $products = $this->productRepo->findBy(['id' => $productIds]);
    $productsById = [];
    foreach ($products as $p) $productsById[$p->getId()] = $p;

    $order = OrderMapper::toEntity($dto, $user, $productsById);

    if ($order->getStatus() === 'PAID') {
        foreach ($order->getItems() as $item) {
            $product = $item->getProduct();
            $stock = $product->getStocks()->first(); 
            if ($stock->getQuantity() < $item->getQuantity()) {
                throw new \Exception("Stock insuffisant pour le produit " . $product->getName());
            }
            $stock->setQuantity($stock->getQuantity() - $item->getQuantity());
        }
    }

    $this->em->persist($order);
    $this->em->flush();

    return OrderMapper::toResponse($order);

   }


    public function update(int $id, object $dto): ?OrderResponse
   {
    $order = $this->orderRepo->find($id);
    if (!$order) return null;

    if (!$dto instanceof OrderAndOrderItemRequest) {
        throw new \InvalidArgumentException("DTO must be OrderAndOrderItemRequest");
    }

    $user = $this->userRepo->find($dto->userId);
    if (!$user) throw new \Exception("User not found");

    $productIds = array_map(fn($i) => $i->productId, $dto->items);
    $products = $this->productRepo->findBy(['id' => $productIds]);
    $productsById = [];
    foreach ($products as $p) $productsById[$p->getId()] = $p;

    if ($order->getStatus() === 'PAID') {
        foreach ($order->getItems() as $item) {
            $stock = $item->getProduct()->getStocks()->first();
            $stock->setQuantity($stock->getQuantity() + $item->getQuantity());
        }
    }

    foreach ($order->getItems() as $item) {
        $this->em->remove($item);
    }
    $order->getItems()->clear();

    $order->setUser($user)->setStatus($dto->status)
        ->setAddress($dto->address)
        ->setPhone($dto->phone)
         ->setPaymentMethod($dto->paymentMethod);

    foreach ($dto->items as $itemDto) {
        $product = $productsById[$itemDto->productId] ?? null;
        if (!$product) throw new \Exception("Product not found: " . $itemDto->productId);

        $orderItem = OrderItemMapper::toEntity($itemDto, $product);
        $order->addItem($orderItem);
    }

    $total = array_reduce($order->getItems()->toArray(), fn($carry, $i) => $carry + $i->getSubTotal(), 0);
    $order->setTotalAmount($total);

    if ($order->getStatus() === 'PAID') {
        foreach ($order->getItems() as $item) {
            $stock = $item->getProduct()->getStocks()->first();
            if ($stock->getQuantity() < $item->getQuantity()) {
                throw new \Exception("Stock insuffisant pour le produit " . $item->getProduct()->getName());
            }
            $stock->setQuantity($stock->getQuantity() - $item->getQuantity());
        }
    }

    $this->em->flush();

    return OrderMapper::toResponse($order);
    }


    public function delete(int $id): bool
    {
    $order = $this->orderRepo->find($id);
    if (!$order) return false;

    if ($order->getStatus() === 'PAID') {
        foreach ($order->getItems() as $item) {
            $stock = $item->getProduct()->getStocks()->first();
            $stock->setQuantity($stock->getQuantity() + $item->getQuantity());
        }
    }

    $this->em->remove($order);
    $this->em->flush();
    return true;
   }
}