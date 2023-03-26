<?php

namespace App\Entity;

use DateTimeInterface;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * 
 * 
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class Produit
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
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\Column(type="text")
     */
    private $description;


    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $imageName;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @ORM\OneToMany(targetEntity=PurchaseCart::class, mappedBy="produit", orphanRemoval=true)
     */
    private $purchaseCarts;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $promo;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active = 0;

    public function __construct()
    {
        $this->purchaseCarts = new ArrayCollection();
    }

    /**
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate 
     * @return void 
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->nom);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }


    /**
     * Get the value of prix
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set the value of prix
     *
     * @return  self
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * @return Collection<int, PurchaseCart>
     */
    public function getPurchaseCarts(): Collection
    {
        return $this->purchaseCarts;
    }

    public function addPurchaseCart(PurchaseCart $purchaseCart): self
    {
        if (!$this->purchaseCarts->contains($purchaseCart)) {
            $this->purchaseCarts[] = $purchaseCart;
            $purchaseCart->setProduit($this);
        }

        return $this;
    }

    public function removePurchaseCart(PurchaseCart $purchaseCart): self
    {
        if ($this->purchaseCarts->removeElement($purchaseCart)) {
            // set the owning side to null (unless already changed)
            if ($purchaseCart->getProduit() === $this) {
                $purchaseCart->setProduit(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function isPromo(): ?bool
    {
        return $this->promo;
    }

    public function setPromo(?bool $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * Get the value of slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     *
     * @return  self
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get the value of active
     */
    public function getActive()
    {
        return $this->active;
    }
}
