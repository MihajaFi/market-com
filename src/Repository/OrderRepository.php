<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }
    
    public function countOrdersByMonth(): array
    {
    $year = (int) date('Y');
    $conn = $this->getEntityManager()->getConnection();

    $sql = "
        SELECT 
            MONTH(order_date) AS month, 
            COUNT(id) AS total
        FROM `orders`
        WHERE YEAR(order_date) = :year
        GROUP BY MONTH(order_date)
        ORDER BY month
    ";

    return $conn->executeQuery($sql, ['year' => $year])
        ->fetchAllAssociative();
    }
}