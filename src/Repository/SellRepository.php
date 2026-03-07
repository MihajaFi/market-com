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

   public function sumSalesByMonth(): array
{
    $year = (int) date('Y');
    $conn = $this->getEntityManager()->getConnection();

    $sql = "
        SELECT 
            MONTH(sale_date) AS month, 
            SUM(total_sales) AS total
        FROM sell
        WHERE YEAR(sale_date) = :year
        GROUP BY MONTH(sale_date)
        ORDER BY month
    ";

    return $conn->executeQuery($sql, ['year' => $year])
        ->fetchAllAssociative();
}
}
