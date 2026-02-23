<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

class PromotionRequest
{
    #[Assert\NotBlank(message: 'Le nom de la promotion est obligatoire')]
    public int $promotionLoyalty = 0;

    /** @var ProductItemRequest[] */
    public array $productItems = [];

}
