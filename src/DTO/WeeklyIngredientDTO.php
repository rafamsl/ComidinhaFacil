<?php

namespace App\DTO;

use App\Entity\WeeklyIngredient;

class WeeklyIngredientDTO
{
    public $name;
    public $amount;
    public $unit;
    public $recipes = [];

    public function __construct(WeeklyIngredient $weeklyIngredient){
        $this->name = $weeklyIngredient->getIngredient()->getName();
        $this->amount = $weeklyIngredient->getAmount();
        $this->unit = $weeklyIngredient->getIngredient()->getUnit();
        $this->recipes[] = $weeklyIngredient->getRecipe()->getName();

    }


}