<?php

namespace App\Entity;

use App\Repository\FVOrderDetailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FVOrderDetailRepository::class)]
class FVOrderDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'fVOrderDetails')]
    private ?FVOrder $myOrder = null;

    #[ORM\Column(length: 255)]
    private ?string $dishName = null;

    #[ORM\Column(length: 255)]
    private ?string $dishImage = null;

    #[ORM\Column]
    private ?int $dishQuantity = null;

    #[ORM\Column]
    private ?float $dishPrice = null;

    #[ORM\Column]
    private ?float $dishTva = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMyOrder(): ?FVOrder
    {
        return $this->myOrder;
    }

    public function setMyOrder(?FVOrder $myOrder): static
    {
        $this->myOrder = $myOrder;

        return $this;
    }

    public function getDishName(): ?string
    {
        return $this->dishName;
    }

    public function setDishName(string $dishName): static
    {
        $this->dishName = $dishName;

        return $this;
    }

    public function getDishImage(): ?string
    {
        return $this->dishImage;
    }

    public function setDishImage(string $dishImage): static
    {
        $this->dishImage = $dishImage;

        return $this;
    }

    public function getDishQuantity(): ?int
    {
        return $this->dishQuantity;
    }

    public function setDishQuantity(int $dishQuantity): static
    {
        $this->dishQuantity = $dishQuantity;

        return $this;
    }

    public function getDishPrice(): ?float
    {
        return $this->dishPrice;
    }

    public function setDishPrice(float $dishPrice): static
    {
        $this->dishPrice = $dishPrice;

        return $this;
    }

    public function getDishTva(): ?float
    {
        return $this->dishTva;
    }

    public function setDishTva(float $dishTva): static
    {
        $this->dishTva = $dishTva;

        return $this;
    }
}
