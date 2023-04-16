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
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ApiResource(
    operations:[
        new Get(),
        new GetCollection(),
        new Post(),
        new Delete()
    ],
    normalizationContext:[
        'groups' => ['recipe:read']
    ],
    denormalizationContext: [
        'groups' => ['recipe:write']
    ],
    paginationClientItemsPerPage: true
)]
#[ApiFilter(SearchFilter::class, properties: ['owner.id'=>'exact', 'description' => 'partial', 'name' => 'partial'])]
#[ApiFilter(PropertyFilter::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe:read', 'user:read', 'weeklyRecipe:read'])]
    public ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recipe:read', 'recipe:write', 'user:read', 'weeklyRecipe:read'])]
    public ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeIngredient::class, orphanRemoval: true)]
    #[Groups(['recipe:write', 'recipe:read'])]
    public Collection $recipeIngredients;

    #[ORM\Column(length: 3000, nullable: true)]
    #[Groups(['recipe:read','recipe:write'])]
    public ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['recipe:read'])]
    #[Assert\NotBlank]
    public ?User $owner = null;

    public function __construct()
    {
        $this->recipeIngredients = new ArrayCollection();
    }

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

    public function getRecipeIngredientsDTO():  ArrayCollection
    {
        $recipeIngredientDTO = new ArrayCollection([]);
        foreach ($this->recipeIngredients as $recipeIngredient){
            $recipeIngredientDTO->add([
                'name' => $recipeIngredient->getIngredient()->getName(),
                'amount' => $recipeIngredient->getAmount()
            ]);
        }

        return $recipeIngredientDTO;
    }

    /**
     * @return Collection
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->add($recipeIngredient);
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
}
