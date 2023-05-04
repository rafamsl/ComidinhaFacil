<?php

namespace App\DataFixtures;

class TestFixtures
{
    public string $hashedPassword = '$2y$13$HWJwlgP38iELk8dOTGo0fenJFRZkTOgke3exQqivSjFEtaF2cvF9m';
    public string $password = 'senha';
    public string $email = '$test_email@gmail.com';
    public string $recipeName = "Spaghetti al'Ammatriciana";
    public string $recipeDescription = "Classic Italian dish made with tomato sauce and bacon";
    public array $ingredient1 = [
        "name" => "garlic",
        "unit" => "unit",
        "amount" => 0.1
    ];
    public array $ingredient2 = [
        "name" => "spaghetti",
        "unit" => "g",
        "amount" => 500
    ];
    public array $ingredient3 = [
        "name" => "bacon",
        "unit" => "g",
        "amount" => 200
    ];
    public array $ingredient4 = [
        "name" => "onion",
        "unit" => "g",
        "amount" => 500
    ];
    public array $ingredient5 = [
        "name" => "olive oil",
        "unit" => "ml",
        "amount" => 200
    ];
}