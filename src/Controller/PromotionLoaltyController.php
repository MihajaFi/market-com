<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class PromotionLoaltyController extends AbstractController
{
    #[Route('/promotion/loaylty', name: 'app_promotion_loaylty')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PromotionLoayltyController.php',
        ]);
    }
}
