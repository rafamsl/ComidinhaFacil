<?php

namespace App\DTO;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use phpDocumentor\Reflection\Types\Integer;

class RecipeIngredientsDTO
{
    public $ingredient;
    public $recipe;
    public $amount;

    public function __construct($ingredient, $recipe, float $amount)
    {
        $this->ingredient = $ingredient;
        $this->recipe = $recipe;
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getIngredient(): Ingredient
    {
        return $this->ingredient;
    }

    /**
     * @param mixed $ingredient
     */
    public function setIngredient($ingredient): void
    {
        $this->ingredient = $ingredient;
    }

    /**
     * @return mixed
     */
    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    /**
     * @param mixed $recipe
     */
    public function setRecipe($recipe): void
    {
        $this->recipe = $recipe;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }


}