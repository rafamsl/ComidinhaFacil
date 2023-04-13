<?php

namespace App\DTO;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;

class RecipesResponseDto
{
    public $id;
    public $name;
    public $description;
    public $owner;
    public $recipeIngredients;

    public function __construct(Recipe $recipe)
    {
        $this->id = $recipe->getId();
        $this->name = $recipe->getName();
        $this->description = $recipe->getDescription();
        $this->owner = $recipe->getOwner();
        $this->recipeIngredients = $recipe->getRecipeIngredients()->getValues();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getRecipeIngredients()
    {
        return $this->recipeIngredients;
    }

    /**
     * @param mixed $recipeIngredients
     */
    public function setRecipeIngredients($recipeIngredients): void
    {
        $this->recipeIngredients = $recipeIngredients;
    }



    public function getName()
    {
        return $this->recipe->getName();
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner): void
    {
        $this->owner = $owner ;
    }

}