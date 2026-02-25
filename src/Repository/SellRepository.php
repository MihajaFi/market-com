<?php

namespace App\Repository;

use App\Entity\Sell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Merchant;

class SellRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sell::class);
    }
    
 
public function getTotalSalesByMerchant(Merchant $merchant): int
{
    return (int) $this->createQueryBuilder('s')
        ->select('COUNT(s.id)')
        ->where('s.merchant = :merchant')
        ->setParameter('merchant', $merchant)
        ->getQuery()
        ->getSingleScalarResult();
}
}
