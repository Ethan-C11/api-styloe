<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PenRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PenRepository::class)]
class Pen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['pen:read', 'pen:create'])]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Groups(['pen:read', 'pen:create'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['pen:read', 'pen:create'])]
    private ?float $price = null;

    #[ORM\Column(length: 150)]
    #[Groups(['pen:read', 'pen:create'])]
    private ?string $description = null;

    #[ORM\Column(length: 30)]
    #[Groups(['pen:read', 'pen:create'])]
    private ?string $ref = null;

    #[ORM\ManyToOne(inversedBy: 'pens')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['pen:read', 'pen:create'])]
    private ?Type $type = null;

    #[ORM\ManyToOne(inversedBy: 'pens')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['pen:read', 'pen:create'])]
    private ?Material $Material = null;

    #[ORM\ManyToMany(targetEntity: Color::class, inversedBy: 'pens')]
    #[Groups(['pen:read', 'pen:create'])]
    private Collection $color;

    #[ORM\ManyToOne(inversedBy: 'pens')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['pen:read', 'pen:create'])]
    private ?Brand $brand = null;

    
    public function __construct()
    {
        $this->color = new ArrayCollection();
    }

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): static
    {
        $this->ref = $ref;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getMaterial(): ?Material
    {
        return $this->Material;
    }

    public function setMaterial(?Material $Material): static
    {
        $this->Material = $Material;

        return $this;
    }

    /**
     * @return Collection<int, Color>
     */
    public function getColor(): Collection
    {
        return $this->color;
    }

    public function addColor(Color $color): static
    {
        if (!$this->color->contains($color)) {
            $this->color->add($color);
        }

        return $this;
    }

    public function removeColor(Color $color): static
    {
        $this->color->removeElement($color);

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }
}
