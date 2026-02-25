<?php
namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CategoryRequest
{
    #[Assert\NotBlank(message: 'Le nom du produit est obligatoire')]
    public string $name = '';
  
    #[Assert\NotBlank(message: 'La description du produit est obligatoire')]
    public string $description = '';

    public ?string $color = '';
}