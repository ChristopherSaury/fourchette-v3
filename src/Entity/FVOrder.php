<?php

namespace App\Entity;

use App\Repository\FVOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FVOrderRepository::class)]
class FVOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column]
    private ?int $state = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $delivery = null;

    /**
     * @var Collection<int, FVOrderDetail>
     */
    #[ORM\OneToMany(targetEntity: FVOrderDetail::class, mappedBy: 'myOrder', cascade:['persist'])]
    private Collection $fVOrderDetails;

    #[ORM\Column(length: 255)]
    private ?string $carrierName = null;

    #[ORM\Column]
    private ?float $carrierPrice = null;

    #[ORM\ManyToOne(inversedBy: 'fVOrders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FVUser $user = null;

    public function __construct()
    {
        $this->fVOrderDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getDelivery(): ?string
    {
        return $this->delivery;
    }

    public function setDelivery(string $delivery): static
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * @return Collection<int, FVOrderDetail>
     */
    public function getFVOrderDetails(): Collection
    {
        return $this->fVOrderDetails;
    }

    public function addFVOrderDetail(FVOrderDetail $fVOrderDetail): static
    {
        if (!$this->fVOrderDetails->contains($fVOrderDetail)) {
            $this->fVOrderDetails->add($fVOrderDetail);
            $fVOrderDetail->setMyOrder($this);
        }

        return $this;
    }

    public function removeFVOrderDetail(FVOrderDetail $fVOrderDetail): static
    {
        if ($this->fVOrderDetails->removeElement($fVOrderDetail)) {
            // set the owning side to null (unless already changed)
            if ($fVOrderDetail->getMyOrder() === $this) {
                $fVOrderDetail->setMyOrder(null);
            }
        }

        return $this;
    }

    public function getCarrierName(): ?string
    {
        return $this->carrierName;
    }

    public function setCarrierName(string $carrierName): static
    {
        $this->carrierName = $carrierName;

        return $this;
    }

    public function getCarrierPrice(): ?float
    {
        return $this->carrierPrice;
    }

    public function setCarrierPrice(float $carrierPrice): static
    {
        $this->carrierPrice = $carrierPrice;

        return $this;
    }

    public function getUser(): ?FVUser
    {
        return $this->user;
    }

    public function setUser(?FVUser $user): static
    {
        $this->user = $user;

        return $this;
    }
    public function getTotalWt(){
        $totalWt = 0;

        $dishes = $this->getFVOrderDetails();
        foreach ($dishes as $dish) {
            $coef = 1 + ($dish->getDishTva() / 100);
            $totalWt+= ($dish->getDishPrice() * $coef) * $dish->getDishQuantity();
        }
        return $totalWt + $this->getCarrierPrice();
    }
    public function getTotalTva(){
        $totalTva = 0;

        $dishes = $this->getFVOrderDetails();
        foreach ($dishes as $dish) {
            $coef = $dish->getDishTva() / 100;
            $totalTva += $dish->getDishPrice() * $coef;
        }
        return $totalTva;
    }
}
