<?php

namespace App\Mapper;

use App\Entity\Merchant;
use App\Dto\Request\MerchantRequest;
use App\Dto\Response\MerchantResponse;
use App\Repository\MerchantRepository;
use App\Repository\SellRepository;

class MerchantMapper
{
    private MerchantRepository $merchantRepository;
    private SellRepository $sellRepository;

   public function __construct(MerchantRepository $merchantRepository, SellRepository $sellRepository)
   {
       $this->merchantRepository = $merchantRepository;
       $this->sellRepository = $sellRepository;
   }
  
     public  function toResponse(Merchant $merchant): MerchantResponse
    {
        $response = new MerchantResponse();
        $response->id = $merchant->getId();
        $response->name = $merchant->getName();
        $response->email = $merchant->getEmail();
        $response->phone = $merchant->getPhone();
        $response->city = $merchant->getCity();
        $response->category = $merchant->getCategory();
        $response->status = $merchant->getStatus();
        $response->joinDate = $merchant->getJoinDate()->format('Y-m-d');

        $response->totalSales =
            $this->sellRepository->getTotalSalesByMerchant($merchant);
        
        $response->avatarColor = $merchant->getAvatarColor();

        return $response;
    }

    public function toResponseByMerchant(Merchant $merchant): MerchantResponse
    {
         $response = new MerchantResponse();
         $response->name = $merchant->getName();
         $response->totalSales =
            $this->orderRepository->getTotalSalesByMerchant($merchant);
        
            return $response;
    }

    public static function toEntity(MerchantRequest $dto): Merchant
    {
        return (new Merchant())
            ->setName($dto->name)
            ->setEmail($dto->email)
            ->setPhone($dto->phone)
            ->setCity($dto->city)
            ->setCategory($dto->category)
            ->setStatus($dto->status)
            ->setJoinDate(new \DateTime())
            ->setAvatarColor($dto->avatarColor);
    }

   
}