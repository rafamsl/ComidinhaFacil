<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Repository\IngredientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[ApiResource(
    operations:[
        new Get(),
        new GetCollection(),
        new Delete()
    ],
    normalizationContext:[
        'groups' => ['ingredient:read']
    ],
    denormalizationContext: [
        'groups' => ['ingredient:write']
    ],
    paginationClientItemsPerPage: true
)]
#[ApiFilter(SearchFilter::class, properties: ['id'=>'exact', 'name' => 'partial'])]
#[ApiFilter(PropertyFilter::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ingredient:read'])]
    public ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['weeklyIngredient:read', 'user:read', 'ingredient:read', 'recipeIngredient:read', 'recipe:read', 'ingredient:write'])]
    public ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['weeklyIngredient:read', 'user:read', 'ingredient:read', 'recipeIngredient:read', 'recipe:read', 'ingredient:write'])]
    public ?string $unit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }
}
