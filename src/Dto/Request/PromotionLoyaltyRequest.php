<?php

namespace App\Dto\Request;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

class PromotionLoyaltyRequest
{
    #[Assert\NotBlank(message: 'promotion type est obligatoire')]
    public string $promotion_type = '';

    #[Assert\Positive(message: 'La valeur de la promotion doit être supérieure à 0')]
    public float $value = 0.0;

    #[Assert\DateTime(message: 'Date et heure gere automatiquement')]
    public DateTime $start_date;
    public DateTime $end_date;

    #[Assert\NotBlank(message: 'conditions est obligatoire')]
    public string $conditions = '';
}
