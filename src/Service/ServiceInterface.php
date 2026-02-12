<?php

namespace App\Service;

interface ServiceInterface
{
    public function findById(int $id): ?object;
    public function findAll(): array;
    public function save(object $dto): object;
    public function update(int $id, object $dto): ?object;
    public function delete(int $id): bool;
}
