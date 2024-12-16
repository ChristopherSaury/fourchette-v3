<?php

namespace App\Entity;

use App\Repository\FVCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FVCategoryRepository::class)]
class FVCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    /**
     * @var Collection<int, FVDish>
     */
    #[ORM\OneToMany(targetEntity: FVDish::class, mappedBy: 'category')]
    private Collection $fVDishes;

    public function __construct()
    {
        $this->fVDishes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, FVDish>
     */
    public function getFVDishes(): Collection
    {
        return $this->fVDishes;
    }

    public function addFVDish(FVDish $fVDish): static
    {
        if (!$this->fVDishes->contains($fVDish)) {
            $this->fVDishes->add($fVDish);
            $fVDish->setCategory($this);
        }

        return $this;
    }

    public function removeFVDish(FVDish $fVDish): static
    {
        if ($this->fVDishes->removeElement($fVDish)) {
            // set the owning side to null (unless already changed)
            if ($fVDish->getCategory() === $this) {
                $fVDish->setCategory(null);
            }
        }

        return $this;
    }
}
