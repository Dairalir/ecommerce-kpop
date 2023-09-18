<?php

namespace App\Entity;

use App\Repository\RecapDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecapDetailsRepository::class)]
class RecapDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'recapDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $OrderProduct = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $product = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $totalRecap = null;

    #[ORM\Column]
    private ?int $id_product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderProduct(): ?Commande
    {
        return $this->OrderProduct;
    }

    public function setOrderProduct(?Commande $OrderProduct): self
    {
        $this->OrderProduct = $OrderProduct;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(string $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTotalRecap(): ?float
    {
        return $this->totalRecap;
    }

    public function setTotalRecap(float $totalRecap): self
    {
        $this->totalRecap = $totalRecap;

        return $this;
    }

    public function getIdProduct(): ?int
    {
        return $this->id_product;
    }

    public function setIdProduct(int $id_product): static
    {
        $this->id_product = $id_product;

        return $this;
    }
}
