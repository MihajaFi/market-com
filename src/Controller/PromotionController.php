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

    /* ================= GET ALL ================= */

    #[Route('', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return $this->json($this->service->findAll());
    }

    /* ================= GET BY ID ================= */

    #[Route('/{id}', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $promotion = $this->service->findById($id);

        if (!$promotion) {
            return $this->json(['message' => 'Promotion not found'], 404);
        }

        return $this->json($promotion);
    }

    /* ================= CREATE ================= */

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        /** @var PromotionRequest $dto */
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            PromotionRequest::class,
            'json'
        );

        if (!$dto->promotionLoyalty || empty($dto->productItems)) {
            return $this->json(['message' => 'promotionLoyalty and productItems are required'], 400);
        }

        $promotion = $this->service->save($dto);

        return $this->json($promotion, 201);
    }

    /* ================= UPDATE ================= */

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        /** @var PromotionRequest $dto */
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            PromotionRequest::class,
            'json'
        );

        if (!$dto->promotionLoyalty || empty($dto->productItems)) {
            return $this->json(['message' => 'promotionLoyalty and productItems are required'], 400);
        }

        $promotion = $this->service->update($id, $dto);

        if (!$promotion) {
            return $this->json(['message' => 'Promotion not found'], 404);
        }

        return $this->json($promotion);
    }

    /* ================= DELETE ================= */

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        if (!$this->service->delete($id)) {
            return $this->json(['message' => 'Promotion not found'], 404);
        }

        return $this->json(['message' => 'Promotion deleted']);
    }
}