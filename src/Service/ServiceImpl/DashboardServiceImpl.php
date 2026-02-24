<?php

namespace App\Service\ServiceImpl;

use App\Repository\MerchantRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\SellRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Dto\Response\DashboardResponse;

class DashboardServiceImpl
{
    public function __construct(
        private MerchantRepository $merchantRepository,
        private OrderRepository $orderRepository,
        private ProductRepository $productRepository,
        private SellRepository $sellRepository,
        private EntityManagerInterface $em
    ) {}

    public function getDashboard(): DashboardResponse
    {
        
        $totalSell = $this->sellRepository->count([]);
        $totalProducts = $this->productRepository->count([]);
        $totalMerchant = $this->merchantRepository->count([]);
        $orderPending = $this->orderRepository->count(['status' => 'PENDING']);

        return new DashboardResponse($totalSell, $totalProducts, $totalMerchant, $orderPending);
    }
}