<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\DTO\WeeklyIngredientDTO;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations:[
        new Get(),
        new GetCollection(),
        new Delete(),
        new Put()
    ],
    normalizationContext:[
        'groups' => ['user:read']
    ],
    denormalizationContext: [
        'groups' => ['user:write']
    ],
    paginationClientItemsPerPage: true
)]
#[ApiFilter(SearchFilter::class, properties: ['id'=>'exact', 'email' => 'partial'])]
#[ApiFilter(PropertyFilter::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'recipe:read','weeklyIngredient:read', 'weeklyRecipe:read'])]
    public ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:read','recipe:read','weeklyIngredient:read', 'weeklyRecipe:read'])]
    public ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user:write'])]
    private ?string $password = null;

    private $plainPassword;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Recipe::class, orphanRemoval: true)]
    #[Groups(['user:read'])]
    public Collection $recipes;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: WeeklyIngredient::class, fetch:"EAGER", orphanRemoval: true)]
    #[Groups(['user:read'])]
    public Collection $weeklyIngredients;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: WeeklyRecipe::class, fetch:"EAGER", orphanRemoval: true)]
    #[Groups(['user:read'])]
    public Collection $weeklyRecipes;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: ApiToken::class, orphanRemoval: true)]
    private Collection $apiTokens;

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
        $this->weeklyIngredients = new ArrayCollection();
        $this->apiTokens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
         $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setOwner($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getOwner() === $this) {
                $recipe->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WeeklyIngredient>
     */
    public function getWeeklyIngredients(): Collection
    {
        return $this->weeklyIngredients;
    }

    public function getWeeklyIngredientsResponse(): ArrayCollection
    {
        $weeklyIngredientsResponse = new ArrayCollection();

        foreach ($this->weeklyIngredients as $weeklyIngredient){
            $ingredientName = $weeklyIngredient->getIngredient()->getName();
            $existingIngredientDTO = $weeklyIngredientsResponse->get($ingredientName);
            if ($existingIngredientDTO) {
                $existingIngredientDTO->amount += $weeklyIngredient->getAmount();
                $existingIngredientDTO->recipes[] = $weeklyIngredient->getRecipe()->getName();
            } else {
                $weeklyIngredientDTO = new WeeklyIngredientDTO($weeklyIngredient);
                $weeklyIngredientsResponse->set($ingredientName, $weeklyIngredientDTO);
            }
        }
        return $weeklyIngredientsResponse;
    }

    public function addWeeklyIngredient(WeeklyIngredient $weeklyIngredient): self
    {
        if (!$this->weeklyIngredients->contains($weeklyIngredient)) {
            $this->weeklyIngredients->add($weeklyIngredient);
            $weeklyIngredient->setOwner($this);
        }

        return $this;
    }

    public function removeWeeklyIngredient(WeeklyIngredient $weeklyIngredient): self
    {
        if ($this->weeklyIngredients->removeElement($weeklyIngredient)) {
            // set the owning side to null (unless already changed)
            if ($weeklyIngredient->getOwner() === $this) {
                $weeklyIngredient->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ApiToken>
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): self
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens->add($apiToken);
            $apiToken->setOwner($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): self
    {
        if ($this->apiTokens->removeElement($apiToken)) {
            // set the owning side to null (unless already changed)
            if ($apiToken->getOwner() === $this) {
                $apiToken->setOwner(null);
            }
        }

        return $this;
    }
}
