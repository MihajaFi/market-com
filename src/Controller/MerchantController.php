<?php

namespace App\Controller;

use App\Dto\Request\MerchantRequest;
use App\Service\ServiceImpl\MerchantServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/merchants')]
class MerchantController extends AbstractController
{
    private MerchantServiceImpl $service;

    public function __construct(MerchantServiceImpl $service)
    {
        $this->service = $service;
    }

    #[Route('', name: 'merchant_list', methods: ['GET'])]
    public function list(): Response
    {
        $merchants = $this->service->findAll();
        return $this->json($merchants);
    }

    #[Route('/{id}', name: 'merchant_get', methods: ['GET'])]
    public function get(int $id): Response
    {
        $merchant = $this->service->findById($id);
        if (!$merchant) {
            return $this->json(['message' => 'Merchant not found'], 404);
        }
        return $this->json($merchant);
    }

    #[Route('', name: 'merchant_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
    $data = json_decode($request->getContent(), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return $this->json(['message' => 'Invalid JSON: ' . json_last_error_msg()], 400);
    }

    $dto = new MerchantRequest();
    $dto->name = $data['name'] ?? '';
    $dto->email = $data['email'] ?? '';
    $dto->phone = $data['phone'] ?? '';
    $dto->city = $data['city'] ?? '';
    $dto->category = $data['category'] ?? '';
    $dto->status = $data['status'] ?? '';
    $dto->avatarColor = $data['avatarColor'] ?? '';

    if (empty($dto->name) || empty($dto->email)) {
        return $this->json(['message' => 'Invalid merchant data'], 400);
    }

    try {
        $merchant = $this->service->save($dto);
        return $this->json($merchant, 201);
    } catch (\Exception $e) {
        return $this->json(['message' => 'Error creating merchant: ' . $e->getMessage()], 500);
    }
    }

   #[Route('/{id}', name: 'merchant_update', methods: ['PUT'])]
public function update(int $id, Request $request): Response
{
    $data = json_decode($request->getContent(), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return $this->json(['message' => 'Invalid JSON: ' . json_last_error_msg()], 400);
    }

    $dto = new MerchantRequest();
    $dto->name = $data['name'] ?? '';
    $dto->email = $data['email'] ?? '';
    $dto->phone = $data['phone'] ?? '';
    $dto->city = $data['city'] ?? '';
    $dto->category = $data['category'] ?? '';
    $dto->status = $data['status'] ?? '';
    $dto->avatarColor = $data['avatarColor'] ?? '';

    if (empty($dto->name) || empty($dto->email)) {
        return $this->json(['message' => 'Invalid merchant data'], 400);
    }

    try {
        $merchant = $this->service->update($id, $dto);
        if (!$merchant) {
            return $this->json(['message' => 'Merchant not found'], 404);
        }
        return $this->json($merchant);
    } catch (\Exception $e) {
        return $this->json(['message' => 'Error updating merchant: ' . $e->getMessage()], 500);
    }
    }

    #[Route('/{id}', name: 'merchant_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $deleted = $this->service->delete($id);
        if (!$deleted) {
            return $this->json(['message' => 'Merchant not found'], 404);
        }
        return $this->json(['message' => 'Merchant deleted successfully']);
    }
}