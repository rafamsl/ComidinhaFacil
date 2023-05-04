<?php

namespace App\Tests\functional;

use ApiPlatform\Symfony\Bundle\Test\Client;
use ApiPlatform\Symfony\Bundle\Test\Response;
use App\DataFixtures\TestFixtures;

trait WeeklyRecipeTest
{
    public function addWeeklyRecipe(Client $client, TestFixtures $testFixtures, array $apiToken, int $recipeId): Response
    {
        $weeklyRecipeResponse = $client->request('POST','/api/weekly_recipes/add/' . $recipeId,[
            'headers' => [
                'Authorization' => 'Bearer ' . $apiToken['apiToken']
            ]
        ]);

        return $weeklyRecipeResponse;
    }
}