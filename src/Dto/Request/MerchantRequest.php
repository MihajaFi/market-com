<?php

namespace App\Dto\Request;

class MerchantRequest
{
    #[Assert\NotBlank(message: 'Le nom du marchand est obligatoire')]
    public string $name = '';

    #[Assert\NotBlank(message: 'L\'email du marchand est obligatoire')]
    public string $email = '';

    #[Assert\NotBlank(message: 'Le numéro de téléphone du marchand est obligatoire')]
    public string $phone = '';

    #[Assert\NotBlank(message: 'La ville du marchand est obligatoire')]
    public string $city = '';
    
    #[Assert\NotBlank(message: 'Le statut du marchand est obligatoire')]
    public string $status = '';
}