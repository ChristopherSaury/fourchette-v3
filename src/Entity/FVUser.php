<?php

namespace App\Entity;

use App\Repository\FVUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FVUserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class FVUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[Assert\Length(min: 8,
    minMessage: 'Votre mot de passe doit contenir au minimum 8 caractères')]
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\Length(min: 2, minMessage: 'Cette entrée doit contenir au minimum 2 caractères')]
    #[Assert\NotBlank(message: 'Veuillez entrer votre prénom')]
    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[Assert\Length(min: 2, minMessage: 'Cette entrée doit contenir au minimum 2 caractères')]
    #[Assert\NotBlank(message: 'Veuillez entrer votre nom')]
    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    /**
     * @var Collection<int, FVAddress>
     */
    #[ORM\OneToMany(targetEntity: FVAddress::class, mappedBy: 'user')]
    private Collection $fVAddresses;

    /**
     * @var Collection<int, FVOrder>
     */
    #[ORM\OneToMany(targetEntity: FVOrder::class, mappedBy: 'user')]
    private Collection $fVOrders;

    public function __construct()
    {
        $this->fVAddresses = new ArrayCollection();
        $this->fVOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function __toString()
    {
        return $this->getFirstname().' '.$this->getLastname();
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return Collection<int, FVAddress>
     */
    public function getFVAddresses(): Collection
    {
        return $this->fVAddresses;
    }

    public function addFVAddress(FVAddress $fVAddress): static
    {
        if (!$this->fVAddresses->contains($fVAddress)) {
            $this->fVAddresses->add($fVAddress);
            $fVAddress->setUser($this);
        }

        return $this;
    }

    public function removeFVAddress(FVAddress $fVAddress): static
    {
        if ($this->fVAddresses->removeElement($fVAddress)) {
            // set the owning side to null (unless already changed)
            if ($fVAddress->getUser() === $this) {
                $fVAddress->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FVOrder>
     */
    public function getFVOrders(): Collection
    {
        return $this->fVOrders;
    }

    public function addFVOrder(FVOrder $fVOrder): static
    {
        if (!$this->fVOrders->contains($fVOrder)) {
            $this->fVOrders->add($fVOrder);
            $fVOrder->setUser($this);
        }

        return $this;
    }

    public function removeFVOrder(FVOrder $fVOrder): static
    {
        if ($this->fVOrders->removeElement($fVOrder)) {
            // set the owning side to null (unless already changed)
            if ($fVOrder->getUser() === $this) {
                $fVOrder->setUser(null);
            }
        }

        return $this;
    }
}
