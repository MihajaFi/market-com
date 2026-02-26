<?php

namespace App\Service\ServiceImpl;

use App\Repository\MerchantRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\SellRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Dto\Response\DashboardResponse;
use App\Dto\Response\SalesByMonthResponse;

class DashboardServiceImpl
{
     private const MONTHS = [
        1 => 'Jan', 2 => 'Fév', 3 => 'Mar', 4 => 'Avr',
        5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Aoû',
        9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc',
    ];

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

    public function getSalesByMonth(): array
    {
        $year = (int) date('Y');

        $salesData = $this->sellRepository->sumSalesByMonth();
        $ordersData = $this->orderRepository->countOrdersByMonth();

        $salesMap = [];
        foreach ($salesData as $s) {
            $salesMap[(int)$s['month']] = (int)$s['total'];
        }

        $ordersMap = [];
        foreach ($ordersData as $o) {
            $ordersMap[(int)$o['month']] = (int)$o['total'];
        }

        $result = [];
        foreach (self::MONTHS as $i => $label) {
            $result[] = new SalesByMonthResponse(
                $label,
                $salesMap[$i] ?? 0,    
                $ordersMap[$i] ?? 0    
            );
        }
        
        return $result;
    }
}
