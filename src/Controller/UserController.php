<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/email')]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private SerializerInterface $serializer
    ) {}

    #[Route('/{email}', name: 'delete_user_by_email', methods: ['DELETE'])]
    public function deleteByEmail(string $email): JsonResponse
    {
        $deleted = $this->userRepository->deleteByEmail($email);

        if (!$deleted) {
            return $this->json([
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        return $this->json([
            'message' => 'Utilisateur supprimé avec succès'
        ], 200);
    }
}