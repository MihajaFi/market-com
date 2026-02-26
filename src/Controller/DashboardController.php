<?php
 
namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ServiceImpl\MerchantServiceImpl;
use App\Service\ServiceImpl\DashboardServiceImpl;
 
#[Route('/api/dashboard')]
class DashboardController extends AbstractController
{
     private DashboardServiceImpl $service;
     private MerchantServiceImpl $merchantServiceImpl;
     
    public function __construct(DashboardServiceImpl $service , MerchantServiceImpl $merchantServiceImpl)
    {
        $this->service = $service;
        $this->merchantServiceImpl = $merchantServiceImpl ;
    }

    #[Route('', name: 'app_dashboard')]
    public function index(): Response
    {
        $result = $this->service->getDashboard();
        return $this->json($result);
    }

    #[Route('/merchants', name: 'sales_merchant', methods: ['GET'])]
    public function listTotalSaleByMerchant(): Response
    {
        $merchants = $this->merchantServiceImpl->findTotalSaleByMerchant();
        return $this->json($merchants);
    }

     #[Route('/sales-by-month', name: 'sales_by_month', methods: ['GET'])]
    public function salesByMonth(): Response
    {
        $salesByMonth = $this->service->getSalesByMonth();
        return $this->json($salesByMonth);
    }

}