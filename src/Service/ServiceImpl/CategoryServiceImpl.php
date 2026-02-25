<?php

namespace App\Service\ServiceImpl;

use App\Dto\Request\CategoryRequest;
use App\Dto\Response\CategoryResponse;
use App\Mapper\CategoryMapper;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ServiceInterface;

class CategoryServiceImpl implements ServiceInterface
{
    private CategoryRepository $repository;
    private EntityManagerInterface $em;
    private CategoryMapper $mapper;

    public function __construct(
        CategoryRepository $repository,
        EntityManagerInterface $em,
        CategoryMapper $mapper
    ) {
        $this->repository = $repository;
        $this->em = $em;
        $this->mapper = $mapper;
    }

    public function findById(int $id): ?CategoryResponse
    {
        $category = $this->repository->find($id);

        return $category
            ? $this->mapper->toResponse($category)
            : null;
    }

    public function findAll(): array
    {
        $categories = $this->repository->findAll();

        return array_map(
            fn($c) => $this->mapper->toResponse($c),
            $categories
        );
    }

    public function save(object $dto): CategoryResponse
    {
        /** @var CategoryRequest $dto */
        $category = CategoryMapper::toEntity($dto);

        $this->em->persist($category);
        $this->em->flush();

        return $this->mapper->toResponse($category);
    }

    public function update(int $id, object $dto): ?CategoryResponse
    {
        /** @var CategoryRequest $dto */
        $category = $this->repository->find($id);
        if (!$category) {
            return null;
        }

        CategoryMapper::updateEntity($category, $dto);

        $this->em->flush();

        return $this->mapper->toResponse($category);
    }

    public function delete(int $id): bool
    {
        $category = $this->repository->find($id);
        if (!$category) {
            return false;
        }

        $this->em->remove($category);
        $this->em->flush();

        return true;
    }
}