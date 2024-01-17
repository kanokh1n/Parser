<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $sku = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private ?int $reviews_count = null;

    #[ORM\Column(nullable: false, options:["default"=> "CURRENT_TIMESTAMP"])]
    private ?\DateTimeImmutable $created_date = null;

    #[ORM\Column(nullable: false, options:["default"=> "CURRENT_TIMESTAMP"])]
    private ?\DateTimeImmutable $updated_date = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Seller $seller = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSku(): ?int
    {
        return $this->sku;
    }

    public function setSku(int $sku): static
    {
        $this->sku = $sku;

        return $this;
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getReviewsCount(): ?int
    {
        return $this->reviews_count;
    }

    public function setReviewsCount(int $reviews_count): static
    {
        $this->reviews_count = $reviews_count;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedDateValue(): void
    {
        $this->created_date = new \DateTimeImmutable();
        $this->updated_date = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedDateValue(): void
    {
        $this->updated_date = new \DateTimeImmutable();
    }

    public function getCreatedDate(): ?\DateTimeImmutable
    {
        return $this->created_date;
    }

    public function getUpdatedDate(): ?\DateTimeImmutable
    {
        return $this->updated_date;
    }

    public function getSeller(): ?Seller
    {
        return $this->seller;
    }

    public function setSeller(?Seller $seller): static
    {
        $this->seller = $seller;

        return $this;
    }
}
