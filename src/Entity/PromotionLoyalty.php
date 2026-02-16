<?php

namespace App\Entity;

use App\Repository\PromotionLoyaltyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromotionLoyaltyRepository::class)]
class PromotionLoyalty
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $promotion_type = null;

    #[ORM\Column]
    private ?float $value = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $start_date = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $end_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $conditions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getPromotionType(): ?string
    {
        return $this->promotion_type;
    }

    public function setPromotionType(?string $promotion_type): static
    {
        $this->promotion_type = $promotion_type;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->start_date;
    }

    public function setStartDate(?\DateTime $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTime $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setConditions(?string $conditions): static
    {
        $this->conditions = $conditions;

        return $this;
    }
}
