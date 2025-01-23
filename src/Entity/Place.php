<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Filter\RadiusFilter;
use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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
    #[Groups(['place:read', 'rating:read', 'user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['place:read', 'place:write'])]
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
    #[Groups(['place:read'])]
    private Collection $tags;


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
}
