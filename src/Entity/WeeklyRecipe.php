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
use App\Repository\WeeklyRecipeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WeeklyRecipeRepository::class)]
#[ApiResource(
    operations:[
        new Get(),
        new GetCollection(),
        new Post(),
        new Delete()
    ],
    normalizationContext:[
        'groups' => ['weeklyRecipe:read']
    ],
    denormalizationContext: [
        'groups' => ['weeklyRecipe:write']
    ],
    paginationClientItemsPerPage: true
)]
#[ApiFilter(SearchFilter::class, properties: ['owner.id'=>'exact','id' => 'exact'])]
#[ApiFilter(PropertyFilter::class)]
class WeeklyRecipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['weeklyRecipe:read', 'user:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user:read' , 'weeklyRecipe:read'])]
    private ?User $owner = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user:read', 'weeklyRecipe:read'])]
    private ?Recipe $recipe = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    #[Groups(['read'])]
    public function getRecipeName(): string{
        return $this->getRecipe()->getName();
    }
}
