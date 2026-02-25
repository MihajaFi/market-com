<?php

namespace App\Controller;

use App\Dto\Request\CategoryRequest;
use App\Service\ServiceImpl\CategoryServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/categories')]
class CategoryController extends AbstractController
{
    private CategoryServiceImpl $service;

    public function __construct(CategoryServiceImpl $service)
    {
        $this->service = $service;
    }

    // GET all categories
    #[Route('', name: 'category_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $categories = $this->service->findAll();
        return new JsonResponse($categories);
    }

    // GET category by ID
    #[Route('/{id}', name: 'category_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $category = $this->service->findById($id);
        if (!$category) {
            return new JsonResponse(['message' => 'Category not found'], 404);
        }
        return new JsonResponse($category);
    }

    // POST create category
    #[Route('', name: 'category_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $dto = new CategoryRequest();
        $dto->name = $data['name'] ?? null;
        $dto->description = $data['description'] ?? '';
        $dto->color = $data['color'] ?? null;

        if (empty($dto->name) || empty($dto->color)) {
            return new JsonResponse(['message' => 'Invalid category data'], 400);
        }

        $category = $this->service->save($dto);

        return new JsonResponse($category, 201);
    }

    // PUT update category
    #[Route('/{id}', name: 'category_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $dto = new CategoryRequest();
        $dto->name = $data['name'] ?? null;
        $dto->description = $data['description'] ?? '';
        $dto->color = $data['color'] ?? null;

        if (empty($dto->name) || empty($dto->color)) {
            return new JsonResponse(['message' => 'Invalid category data'], 400);
        }

        $category = $this->service->update($id, $dto);

        if (!$category) {
            return new JsonResponse(['message' => 'Category not found'], 404);
        }

        return new JsonResponse($category);
    }

    // DELETE category
    #[Route('/{id}', name: 'category_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $deleted = $this->service->delete($id);
        if (!$deleted) {
            return new JsonResponse(['message' => 'Category not found'], 404);
        }
        return new JsonResponse(['message' => 'Category deleted']);
    }
}