<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['book:read']]),
        new GetCollection(normalizationContext: ['groups' => ['book:read']]),
        new Post(denormalizationContext: ['groups' => ['book:write']]),
        new Patch(denormalizationContext: ['groups' => ['book:write']]),
        new Delete(),
    ]
)]

class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['book:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['book:read', 'book:write'])]
    private ?string $isbn = null;

    #[ORM\Column(length: 255)]
    #[Groups(['book:read', 'book:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['book:read', 'book:write'])]
    private ?\DateTimeImmutable $parutionAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['book:read', 'book:write'])]
    private ?string $publisher = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['book:read', 'book:write'])]
    private ?string $resum = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['book:read', 'book:write'])]
    private ?string $price = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[Groups(['book:read', 'book:write'])]
    private ?Author $author = null;

    /**
     * @var Collection<int, Categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'books')]
    #[Groups(['book:read', 'book:write'])]
    private Collection $categorie;

    public function __construct()
    {
        $this->categorie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getParutionAt(): ?\DateTimeImmutable
    {
        return $this->parutionAt;
    }

    public function setParutionAt(?\DateTimeImmutable $parutionAt): static
    {
        $this->parutionAt = $parutionAt;

        return $this;
    }

    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    public function setPublisher(?string $publisher): static
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getResum(): ?string
    {
        return $this->resum;
    }

    public function setResum(?string $resum): static
    {
        $this->resum = $resum;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategorie(): Collection
    {
        return $this->categorie;
    }

    public function addCategorie(Categorie $categorie): static
    {
        if (!$this->categorie->contains($categorie)) {
            $this->categorie->add($categorie);
        }

        return $this;
    }

    public function removeCategorie(Categorie $categorie): static
    {
        $this->categorie->removeElement($categorie);

        return $this;
    }
}
