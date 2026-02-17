<?php

namespace App\Controller;

use App\Dto\Request\PromotionRequest;
use App\Service\ServiceImpl\PromotionServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/promotions')]
class PromotionController extends AbstractController
{
    private PromotionServiceImpl $service;
    private SerializerInterface $serializer;

    public function __construct(
        PromotionServiceImpl $service,
        SerializerInterface $serializer
    ) {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    // GET all
    #[Route('', name: 'promotion_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $promotions = $this->service->findAll();
        return new JsonResponse($promotions);
    }

    // GET by ID
    #[Route('/{id}', name: 'promotion_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $promotion = $this->service->findById($id);

        if (!$promotion) {
            return new JsonResponse(['message' => 'Promotion not found'], 404);
        }

        return new JsonResponse($promotion);
    }

    // POST create
    #[Route('', name: 'promotion_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        /** @var PromotionRequest $promotionRequest */
        $promotionRequest = $this->serializer->deserialize(
            $request->getContent(),
            PromotionRequest::class,
            'json'
        );

        if (empty($promotionRequest->promotionLoyalty) || empty($promotionRequest->product)) {
            return new JsonResponse(['message' => 'Invalid promotion data'], 400);
        }

        $promotion = $this->service->save($promotionRequest);

        return new JsonResponse($promotion, 201);
    }

    // PUT update
    #[Route('/{id}', name: 'promotion_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        /** @var PromotionRequest $promotionRequest */
        $promotionRequest = $this->serializer->deserialize(
            $request->getContent(),
            PromotionRequest::class,
            'json'
        );

        if (empty($promotionRequest->promotionLoyalty) || empty($promotionRequest->product)) {
            return new JsonResponse(['message' => 'Invalid promotion data'], 400);
        }

        $promotion = $this->service->update($id, $promotionRequest);

        if (!$promotion) {
            return new JsonResponse(['message' => 'Promotion not found'], 404);
        }

        return new JsonResponse($promotion);
    }

    // DELETE
    #[Route('/{id}', name: 'promotion_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return new JsonResponse(['message' => 'Promotion not found'], 404);
        }

        return new JsonResponse(['message' => 'Promotion deleted']);
    }
}
