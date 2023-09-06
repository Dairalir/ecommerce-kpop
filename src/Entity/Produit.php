<?php

namespace App\Entity;


use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ApiResource(
    normalizationContext: [ "groups" => ["read:product"]]
    )]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["read:product"])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(["read:product"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["read:product"])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[Groups(["read:product"])]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    #[Groups(["read:product"])]
    private ?string $picture = null;

    #[ORM\Column]
    #[Groups(["read:product"])]
    private ?int $stock = null;

    #[ORM\Column]
    #[Groups(["read:product"])]
    private ?bool $active = null;

    #[ORM\ManyToMany(targetEntity: SousRubrique::class, inversedBy: 'produits')]
    #[Groups(["read:product"])]
    private Collection $sous_rubrique;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["read:product"])]
    private ?Fournisseur $fournisseur = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'favoris')]
    private Collection $favoris;

    public function __construct()
    {
        $this->sous_rubrique = new ArrayCollection();
        $this->favoris = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection<int, SousRubrique>
     */
    public function getSousRubrique(): Collection
    {
        return $this->sous_rubrique;
    }

    public function addSousRubrique(SousRubrique $sousRubrique): self
    {
        if (!$this->sous_rubrique->contains($sousRubrique)) {
            $this->sous_rubrique->add($sousRubrique);
        }

        return $this;
    }

    public function removeSousRubrique(SousRubrique $sousRubrique): self
    {
        $this->sous_rubrique->removeElement($sousRubrique);

        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(User $favori): static
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
        }

        return $this;
    }

    public function removeFavori(User $favori): static
    {
        $this->favoris->removeElement($favori);

        return $this;
    }
}
