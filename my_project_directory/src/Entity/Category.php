<?php
// src/Entity/Category.php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\CategoryRepository;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\HasLifecycleCallbacks]  
#[ApiResource]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: "Le titre ne peut pas être null.")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le titre doit comporter au moins 2 caractères.",
        maxMessage: "Le titre ne peut pas dépasser 255 caractères."
    )]
    private ?string $title = null;

    #[ORM\ManyToMany(targetEntity: Movie::class, mappedBy: 'categories')]
    private Collection $movies;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)] 
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)] 
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();  
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();  
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): static
    {
        if (!$this->movies->contains($movie)) {
            $this->movies->add($movie);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): static
    {
        $this->movies->removeElement($movie);
        return $this;
    }
}
