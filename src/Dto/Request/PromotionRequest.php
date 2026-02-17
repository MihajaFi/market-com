<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

class PromotionRequest
{
    #[Assert\NotBlank(message: 'Le nom de la promotion est obligatoire')]
    public int $promotionLoyalty = 0;

    #[Assert\NotBlank(message: 'La description de la promotion est obligatoire')]
    public int $product = 0;

}
