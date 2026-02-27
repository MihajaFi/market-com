<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function sumStockByCategory(): array
   {
    return $this->createQueryBuilder('c')
        ->select('c.name AS category, COALESCE(SUM(s.quantity), 0) AS stock')
        ->leftJoin('c.products', 'p')
        ->leftJoin('p.stocks', 's')
        ->groupBy('c.id, c.name')
        ->orderBy('c.name', 'ASC')
        ->getQuery()
        ->getArrayResult();
    }
}