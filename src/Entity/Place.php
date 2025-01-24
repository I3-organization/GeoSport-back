<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Filter\RadiusFilter;
use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlaceRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['place:read']],
    denormalizationContext: ['groups' => ['place:write']]
)]
#[ApiFilter(RadiusFilter::class, properties: ['latitude', 'longitude', 'radius'])]
class Place
{
    public function __construct()
    {
        $this->ratings = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['place:read', 'rating:read', 'user:read', 'review:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['place:read', 'place:write'])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['place:read', 'place:write'])]
    private ?float $latitude = null;

    #[ORM\Column]
    #[Groups(['place:read', 'place:write'])]
    private ?float $longitude = null;

    #[ORM\OneToMany(targetEntity: Rating::class, mappedBy: 'place')]
    #[Groups(['place:read'])]
    private Collection $ratings;

    #[ApiProperty(readable: true, writable: false)]
    #[Groups(['place:read'])]
    private ?float $ratingsMean = null;

    /**
     * @var Collection<int, TagLabel>
     */
    #[ORM\ManyToMany(targetEntity: TagLabel::class, inversedBy: 'places')]
    #[Groups(['place:read', 'place:write'])]
    #[ApiFilter(SearchFilter::class, strategy: 'exact', properties: ['tags.id'])]
    private Collection $tags;

    #[Assert\Url]
    #[ORM\Column]
    #[Groups(['place:read', 'place:write'])]
    private ?string $image = null;


    #[ORM\Column]
    #[Groups(['place:read', 'place:write'])]
    private ?string $address = null;

    #[Assert\Email]
    #[Groups(['place:read', 'place:write'])]
    #[ORM\Column(length: 180, unique: false)]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['place:read', 'place:write'])]
    private ?string $phone = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, TagLabel>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(TagLabel $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(TagLabel $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function setRatings(Collection $ratings): void
    {
        $this->ratings = $ratings;
    }

    public function getRatingsMean(): ?float
    {
        if ($this->ratings->isEmpty()) {
            return null;
        }

        $total = 0;
        foreach ($this->ratings as $rating) {
            $total += $rating->getRate();
        }

        return $total / $this->ratings->count();
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }
}
