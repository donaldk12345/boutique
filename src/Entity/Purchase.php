<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PurchaseRepository::class)
 */
class Purchase
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codepostal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="purchases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userPurchase;

    /**
     * @ORM\OneToMany(targetEntity=PurchaseCart::class, mappedBy="purchase", orphanRemoval=true)
     */
    private $purchaseCart;

    public function __construct()
    {
        $this->purchaseCart = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCodepostal(): ?string
    {
        return $this->codepostal;
    }

    public function setCodepostal(string $codepostal): self
    {
        $this->codepostal = $codepostal;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUserPurchase(): ?User
    {
        return $this->userPurchase;
    }

    public function setUserPurchase(?User $userPurchase): self
    {
        $this->userPurchase = $userPurchase;

        return $this;
    }

    /**
     * @return Collection<int, PurchaseCart>
     */
    public function getPurchaseCart(): Collection
    {
        return $this->purchaseCart;
    }

    public function addPurchaseCart(PurchaseCart $purchaseCart): self
    {
        if (!$this->purchaseCart->contains($purchaseCart)) {
            $this->purchaseCart[] = $purchaseCart;
            $purchaseCart->setPurchase($this);
        }

        return $this;
    }

    public function removePurchaseCart(PurchaseCart $purchaseCart): self
    {
        if ($this->purchaseCart->removeElement($purchaseCart)) {
            // set the owning side to null (unless already changed)
            if ($purchaseCart->getPurchase() === $this) {
                $purchaseCart->setPurchase(null);
            }
        }

        return $this;
    }
}
