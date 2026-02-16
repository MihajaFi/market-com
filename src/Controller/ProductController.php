<?php

namespace App\Controller;

use App\Dto\Request\PromotionLoyaltyRequest;
use App\Dto\Response\PromotionLoyaltyResponse;
use App\Service\ServiceImpl\PromotionLoyaltyServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/PromotionLoyaltys')]
class PromotionLoyaltyController extends AbstractController
{
    private PromotionLoyaltyServiceImpl $service;
    private SerializerInterface $serializer;

    public function __construct(PromotionLoyaltyServiceImpl $service, SerializerInterface $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    // GET all
    #[Route('', name: 'PromotionLoyalty_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $PromotionLoyaltys = $this->service->findAll();
        return new JsonResponse($PromotionLoyaltys); 
    }

    // GET by ID
    #[Route('/{id}', name: 'PromotionLoyalty_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $PromotionLoyalty = $this->service->findById($id);
        if (!$PromotionLoyalty) {
            return new JsonResponse(['message' => 'PromotionLoyalty not found'], 404);
        }
        return new JsonResponse($PromotionLoyalty);
    }

    // POST create
    #[Route('', name: 'PromotionLoyalty_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $PromotionLoyaltyRequest = $this->serializer->deserialize(
            $request->getContent(),
            PromotionLoyaltyRequest::class,
            'json'
        );
        if (empty($PromotionLoyaltyRequest->name) || empty($PromotionLoyaltyRequest->description) || $PromotionLoyaltyRequest->price <= 0) {
            return new JsonResponse(['message' => 'Invalid PromotionLoyalty data'], 400);
        }
        $PromotionLoyalty = $this->service->save($PromotionLoyaltyRequest);
        return new JsonResponse($PromotionLoyalty, 201);
    }

    // PUT update
    #[Route('/{id}', name: 'PromotionLoyalty_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $PromotionLoyaltyRequest = $this->serializer->deserialize(
            $request->getContent(),
            PromotionLoyaltyRequest::class,
            'json'
        );
        if (empty($PromotionLoyaltyRequest->name) || empty($PromotionLoyaltyRequest->description) || $PromotionLoyaltyRequest->price <= 0) {
            return new JsonResponse(['message' => 'Invalid PromotionLoyalty data'], 400);
        }
        $PromotionLoyalty = $this->service->update($id, $PromotionLoyaltyRequest);
        if (!$PromotionLoyalty) {
            return new JsonResponse(['message' => 'PromotionLoyalty not found'], 404);
        }
        return new JsonResponse($PromotionLoyalty);
    }

    // DELETE
    #[Route('/{id}', name: 'PromotionLoyalty_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $deleted = $this->service->delete($id);
        if (!$deleted) {
            return new JsonResponse(['message' => 'PromotionLoyalty not found'], 404);
        }
        return new JsonResponse(['message' => 'PromotionLoyalty deleted']);
    }
}
