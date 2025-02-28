<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\DataPersister\BookDataPersister;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['book:read']],
            security: "is_granted('PUBLIC_ACCESS')"
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['book:read']],
            security: "is_granted('PUBLIC_ACCESS')"
        ),
        new Post(
            denormalizationContext: ['groups' => ['book:write']],
            security: "is_granted('ROLE_USER')",
            processor: BookDataPersister::class,
            securityMessage: "Seuls les utilisateurs connectés peuvent créer des livres"
            // changer en role_PRO et ajouter un message pour les PRO
            // security: "is_granted('ROLE_PRO')",
            // securityMessage: "Seuls les professionnels peuvent créer des livres"
        ),
        new Patch(
            denormalizationContext: ['groups' => ['book:write']],
            security: "is_granted('BOOK_EDIT', object)",
            // security: "is_granted('ROLE_PRO') and is_granted('BOOK_EDIT', object)",
            securityMessage: "Vous ne pouvez modifier que vos propres livres"
        ),
        new Delete(
            security: "is_granted('BOOK_DELETE', object)",
            // security: "is_granted('ROLE_PRO') and is_granted('BOOK_DELETE', object)",
            securityMessage: "Vous ne pouvez supprimer que vos propres livres"
        ),
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

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(['book:read', 'book:write'])]
    private ?int $parutionAt = null;

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

    /**
     * @var Collection<int, Img>
     */
    #[ORM\OneToMany(targetEntity: Img::class, mappedBy: 'book')]
    private Collection $image;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?UserPro $userPro = null;



    public function __construct()
    {
        $this->categorie = new ArrayCollection();
        $this->image = new ArrayCollection();
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

    public function getParutionAt(): ?int
    {
        return $this->parutionAt;
    }

    public function setParutionAt(?int $parutionAt): static
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

    /**
     * @return Collection<int, Img>
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(Img $image): static
    {
        if (!$this->image->contains($image)) {
            $this->image->add($image);
            $image->setBook($this);
        }

        return $this;
    }

    public function removeImage(Img $image): static
    {
        if ($this->image->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getBook() === $this) {
                $image->setBook(null);
            }
        }

        return $this;
    }

    public function getUserPro(): ?UserPro
    {
        return $this->userPro;
    }

    public function setUserPro(?UserPro $userPro): static
    {
        $this->userPro = $userPro;

        return $this;
    }


}
