<?php

namespace App\Document;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch(),
        new Put(),
        new Delete()
    ],
    normalizationContext: ['groups' => ['article:read']],
    denormalizationContext: ['groups' => ['article:create', 'article:update']]
)]
#[HasLifecycleCallbacks]
#[ODM\Document(collection: 'articles')]
class Article
{
    #[ApiProperty()]
    #[Groups(['article:read'])]
    #[ODM\Id()]
    private ?string  $id;

    #[ApiProperty()]
    #[Groups(['article:read', 'article:create', 'article:update'])]
    #[ODM\Field(type: 'string')]
    private ?string $name;

    #[ApiProperty()]
    #[Groups(['article:read', 'article:create', 'article:update'])]
    #[ODM\Field(type: 'string')]
    private ?string $description;

    #[ApiProperty()]
    #[Groups(['article:read', 'article:create', 'article:update'])]
    #[ODM\Field(type: 'float')]
    private ?float $price;

    #[ApiProperty()]
    #[Groups(['article:read', 'article:create', 'article:update'])]
    #[ODM\Field(type: 'int')]
    private ?int $quantity;

    #[ApiProperty()]
    #[Groups(['article:read'])]
    #[ODM\Field(type: 'date_immutable')]
    private ?\DateTimeImmutable $createdAt;

    #[ApiProperty()]
    #[Groups(['article:read'])]
    #[ODM\Field(type: 'date_immutable')]
    private ?\DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    #[ODM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
