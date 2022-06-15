<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;

/**
 * 
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
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
     * 
     * 
     * @var File|null
     * @Vich\UploadableField(mapping="produit_images", fileNameProperty="imageName")
     * 
     */
    private $imageFile;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @ORM\OneToMany(targetEntity=PurchaseCart::class, mappedBy="produit", orphanRemoval=true)
     */
    private $purchaseCarts;

    public function __construct()
    {
        $this->purchaseCarts = new ArrayCollection();
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
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setcreatedAt(new \DateTimeImmutable);
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
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
}
