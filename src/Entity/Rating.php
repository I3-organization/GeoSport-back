<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => ['rating:read']],
    denormalizationContext: ['groups' => ['rating:write']]
)]
#[ORM\Entity(repositoryClass: RatingRepository::class)]
#[ORM\UniqueConstraint(columns: ['user_id', 'place_id'])]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['rating:read', 'user:read', 'place:read'])]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['rating:read', 'rating:write'])]
    #[ApiFilter(SearchFilter::class, strategy: 'exact', properties: ['user.id'])]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Place::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['rating:read', 'rating:write', 'user:read'])]
    #[ApiFilter(SearchFilter::class, strategy: 'exact', properties: ['place.id'])]
    private ?Place $place = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\Range(min: 1, max: 5)]
    #[Groups(['rating:read', 'rating:write', 'user:read', 'place:read'])]
    private ?int $rate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}
