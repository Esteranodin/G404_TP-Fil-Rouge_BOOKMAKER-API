<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get ;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\ImgRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ImgRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['img:read']]),
        new GetCollection(normalizationContext: ['groups' => ['img:read']]),
        new Post(denormalizationContext: ['groups' => ['img:write']]),
        new Patch(denormalizationContext: ['groups' => ['img:write']]),
        new Delete(),
    ]
)]
class Img
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['img:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['img:read', 'img:write'])]
    private ?string $imgPath = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['img:read', 'img:write'])]
    private ?string $imgAlt = null;

    #[ORM\ManyToOne(inversedBy: 'image')]
    private ?Book $book = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }

    public function setImgPath(?string $imgPath): static
    {
        $this->imgPath = $imgPath;

        return $this;
    }

    public function getImgAlt(): ?string
    {
        return $this->imgAlt;
    }

    public function setImgAlt(?string $imgAlt): static
    {
        $this->imgAlt = $imgAlt;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }
}
