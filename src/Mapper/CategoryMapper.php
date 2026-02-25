<?php

namespace App\Mapper;

use App\Entity\Category;
use App\Dto\Request\CategoryRequest;
use App\Dto\Response\CategoryResponse;

class CategoryMapper
{
    public static function toEntity(CategoryRequest $dto): Category
    {
        $category = new Category();
        $category->setName($dto->name)
                 ->setDescription($dto->description)
                 ->setColor($dto->color);

        return $category;
    }

    public static function toResponse(Category $category): CategoryResponse
    {
        $response = new CategoryResponse();
        $response->id = $category->getId();
        $response->name = $category->getName();
        $response->description = $category->getDescription();
        $response->color = $category->getColor();
        $response->productCount = $category->getProducts()->count(); 

        return $response;
    }

    public static function updateEntity(Category $category, CategoryRequest $dto): Category
    {
        $category->setName($dto->name)
                 ->setDescription($dto->description)
                 ->setColor($dto->color);

        return $category;
    }
}