<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $product_id = null;

    #[ORM\Column]
    private ?int $sku = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $reviews_count = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $created_date = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $updated_date = null;

    #[ORM\ManyToOne(inversedBy: 'product_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Seller $seller_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getproductId(): ?int
    {
        return $this->product_id;
    }

    public function setproductId(int $product_id): static
    {
        $this->product_id = $product_id;

        return $this;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

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

    public function getReviewsCount(): ?int
    {
        return $this->reviews_count;
    }

    public function setReviewsCount(int $reviews_count): static
    {
        $this->reviews_count = $reviews_count;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeImmutable
    {
        return $this->created_date;
    }

    public function setCreatedDate(\DateTimeImmutable $created_date): static
    {
        $this->created_date = $created_date;

        return $this;
    }

    public function getUpdatedDate(): ?\DateTimeImmutable
    {
        return $this->updated_date;
    }

    public function setUpdatedDate(\DateTimeImmutable $updated_date): static
    {
        $this->updated_date = $updated_date;

        return $this;
    }

    public function getSellerId(): ?Seller
    {
        return $this->seller_id;
    }

    public function setSellerId(?Seller $seller_id): static
    {
        $this->seller_id = $seller_id;

        return $this;
    }
}
