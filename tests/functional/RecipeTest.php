<?php

namespace App\Tests\functional;

use ApiPlatform\Symfony\Bundle\Test\Client;
use ApiPlatform\Symfony\Bundle\Test\Response;
use App\DataFixtures\TestFixtures;

trait RecipeTest
{

    public function createRecipe(Client $client, TestFixtures $testFixtures, array $apiToken): Response
    {
        $recipeResponse = $client->request('POST','/api/recipes',[
            'headers' => [
                'Authorization' => 'Bearer ' . $apiToken['apiToken']
            ],
            'json' => [
                'name' => $this->testFixtures->recipeName,
                'description' => $this->testFixtures->recipeDescription,
                'owner' => $apiToken['user.id'],
                'ingredients' => array($this->testFixtures->ingredient1, $this->testFixtures->ingredient2, $this->testFixtures->ingredient3),
            ]
        ]);

        return $recipeResponse;

    }

}