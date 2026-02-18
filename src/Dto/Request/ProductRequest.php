<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductRequest
{
    #[Assert\NotBlank(message: 'Le nom du produit est obligatoire')]
    public string $name = '';

    #[Assert\NotBlank(message: 'La description du produit est obligatoire')]
    public string $description = '';

    #[Assert\NotBlank(message: 'La catégorie du produit est obligatoire')]
    public string $category = '';

    #[Assert\Positive(message: 'Le prix doit être supérieur à 0')]
    public float $price = 0.0;

    public ?UploadedFile $image = null;
}
