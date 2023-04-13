<?php

namespace App\DTO;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;

class WeeklyRecipeDTO
{
    public $owner;
    public $recipe;

    public function __construct($owner, $recipe)
    {
        $this->owner = $owner;
        $this->recipe = $recipe;
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
        $this->owner = $owner;
    }

    /**
     * @return mixed
     */
    public function getListOfRecipes()
    {
        return $this->listOfRecipes;
    }

    /**
     * @param mixed $listOfRecipes
     */
    public function setListOfRecipes($listOfRecipes): void
    {
        $this->listOfRecipes = $listOfRecipes;
    }

}