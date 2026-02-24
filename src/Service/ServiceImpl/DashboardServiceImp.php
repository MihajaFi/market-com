<?php

namespace App\Service\ServiceImpl;

use App\Repository\MerchantRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\SellRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Dto\Response\DashboardResponse;

class DashboardServiceImp {
    private MerchantRepository $merchantRepository;
    private OrderRepository $orderRepository;
    private ProductRepository $productRepository;
    private SellRepository $sellRepository;
    private EntityManagerInterface $em;

     public function __construct(
         MerchantRepository $merchantRepository,
         OrderRepository $orderRepository,
         ProductRepository $productRepository,
         SellRepository $sellRepository,
         EntityManagerInterface $em
    ) {
        $this->merchantRepository = $merchantRepository;
        $this->em = $em;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->sellRepository = $sellRepository;

    }



}