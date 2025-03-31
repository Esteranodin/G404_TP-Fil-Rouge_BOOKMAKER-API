<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use App\Repository\UserProRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserProRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['userPro:read']]
        ),
        new Get(
            normalizationContext: ['groups' => ['userPro:read']],
            security: "is_granted('ROLE_USER') and object.getUser() == user or is_granted('ROLE_ADMIN')"
        ),
        new Put(
            denormalizationContext: ['groups' => ['userPro:update']],
            security: "(is_granted('ROLE_USER_PRO') and object.getUser() == user and object.isValidated() == true) or is_granted('ROLE_ADMIN')"
        )
    ]
)]
class UserPro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['userPro:read'])]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'userPro', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['userPro:read', 'admin:write'])]
    private bool $isValidated = false;
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['userPro:read', 'userPro:update'])]
    private ?string $companyName = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['userPro:read', 'userPro:update'])]
    private ?string $companyAdress = null;
    
    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['userPro:read', 'userPro:update'])]
    private ?string $phone = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['userPro:read'])]
    private \DateTimeImmutable $requestedAt;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: 'userPro', )]
    private Collection $books;

    // Constructeur pour initialiser requestedAt
    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->isValidated = false;
        $this->requestedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function isValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $isValidated): static
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): static
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getCompanyAdress(): ?string
    {
        return $this->companyAdress;
    }

    public function setCompanyAdress(?string $companyAdress): static
    {
        $this->companyAdress = $companyAdress;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setUserPro($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getUserPro() === $this) {
                $book->setUserPro(null);
            }
        }

        return $this;
    }
}
