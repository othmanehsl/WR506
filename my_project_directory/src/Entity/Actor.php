<?php
// src/Entity/Actor.php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Repository\ActorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ActorRepository::class)]
#[ORM\HasLifecycleCallbacks]  
#[ApiResource]
#[ApiFilter(SearchFilter::class, properties: [
    'lastname' => 'partial',     
    'firstname' => 'partial',    
    'nationality' => 'exact',   
    'gender' => 'exact'         
])]
#[ApiFilter(RangeFilter::class, properties: [
    'awards',                   
    'dob',                
    'deathDate'              
])]
#[ApiFilter(DateFilter::class, properties: [
    'dob',                      
    'createdAt',                
    'deathDate'          
])]
#[ApiFilter(BooleanFilter::class, properties: [
    'isRetired'             
])]
#[ApiFilter(OrderFilter::class, properties: [
    'lastname',               
    'dob',                 
    'awards',                   
    'deathDate'                
], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(ExistsFilter::class, properties: [
    'media',
    'bio',                
    'deathDate'             
])]
#[ApiFilter(RangeFilter::class, properties: [
    'awards'              
])]
#[ApiFilter(PropertyFilter::class)] 
class Actor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: "Le nom ne peut pas être nul.")]
    #[Assert\Length(min: 2, max: 30, minMessage: "Le nom doit comporter au moins 2 caractères.", maxMessage: "Le nom ne peut pas dépasser 30 caractères.")]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 2, max: 30, minMessage: "Le prénom doit comporter au moins 2 caractères.", maxMessage: "Le prénom ne peut pas dépasser 30 caractères.")]
    private ?string $firstname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dob = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: "La nationalité ne peut pas être nulle.")]
    #[Assert\Country(message: "La nationalité doit être un pays valide.")]
    private ?string $nationality = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $media = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: "Le genre ne peut pas être nul.")]
    #[Assert\Choice(choices: ['male', 'female', 'non-binary'], message: "Le genre doit être 'male', 'female' ou 'non-binary'.")]
    private ?string $gender = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: 0, max: 5, notInRangeMessage: "La notation doit être comprise entre 0 et 5.")]
    private ?int $awards = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $deathDate = null;

    /**
     * @var Collection<int, Movie>
     */
    #[ORM\ManyToMany(targetEntity: Movie::class, mappedBy: 'actors')]
    private Collection $movies;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
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
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getDob(): ?\DateTimeInterface
    {
        return $this->dob;
    }

    public function setDob(\DateTimeInterface $dob): static
    {
        $this->dob = $dob;

        return $this;
    }

    public function getDeathDate(): ?\DateTimeInterface
    {
        return $this->deathDate;
    }

    public function setDeathDate(?\DateTimeInterface $deathDate): static
    {
        $this->deathDate = $deathDate;

        return $this;
    }

    public function getAwards(): ?int
    {
        return $this->awards;
    }

    public function setAwards(?int $awards): static
    {
        $this->awards = $awards;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): static
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(?string $media): static
    {
        $this->media = $media;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
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
            $movie->addActor($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): static
    {
        if ($this->movies->removeElement($movie)) {
            $movie->removeActor($this);
        }

        return $this;
    }
}
