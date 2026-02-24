<?php
 
namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ServiceImpl\DashboardServiceImpl;
 
#[Route('/api/dashboard')]
class DashboardController extends AbstractController
{
     private DashboardServiceImpl $service;
     
    public function __construct(DashboardServiceImpl $service)
    {
        $this->service = $service;
    }

    #[Route('', name: 'app_dashboard')]
    public function index(): Response
    {
        $result = $this->service->getDashboard();
        return$this->json($result);
    }


}