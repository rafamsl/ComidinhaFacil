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
use App\Repository\WeeklyIngredientsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WeeklyIngredientsRepository::class)]
#[ApiResource(
    operations:[
        new Get(),
        new GetCollection()
    ],
    normalizationContext:[
        'groups' => ['weeklyIngredient:read']
    ],
    denormalizationContext: [
        'groups' => ['weeklyIngredient:write']
    ],
    paginationClientItemsPerPage: true
)]
#[ApiFilter(SearchFilter::class, properties: ['owner.id'=>'exact','owner.email'=>'exact', 'id' => 'exact'])]
#[ApiFilter(PropertyFilter::class)]
class WeeklyIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['weeklyIngredient:read', 'user:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['weeklyIngredient:read', 'user:read'])]
    public ?Ingredient $ingredient = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    public ?Recipe $recipe = null;

    #[ORM\ManyToOne(inversedBy: 'weeklyIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['weeklyIngredient:read'])]
    public ?User $owner = null;

    #[ORM\Column]
    #[Groups(['weeklyIngredient:read', 'user:read'])]
    public ?float $amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    #[Groups(['read'])]
    public function getIngredientName(): string{
        return $this->ingredient->getName();
    }

}
