<?php

namespace App\Tests\functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Ingredient;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use App\DataFixtures\TestFixtures;

class JourneyTest extends ApiTestCase
{
    use ReloadDatabaseTrait;
    use UserTest, RecipeTest, WeeklyRecipeTest;
    public TestFixtures $testFixtures;
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    public $entityManager;
    public $client;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->testFixtures = new TestFixtures();
        $this->client = self::createClient();
    }

    public function testCreateUser(){
        $client = self::createClient();
        $user = $this->createUser($this->testFixtures, $this->entityManager);
        $this->assertEquals($user->getEmail(),$this->testFixtures->email);
        $this->assertEquals($user->getPassword(),$this->testFixtures->hashedPassword);
    }

    public function testGetToken(){
        $user = $this->createUser($this->testFixtures, $this->entityManager);
        $apiToken = $this->getToken($this->client, $this->testFixtures);
        $this->assertEquals($user->getId(),$apiToken['user.id']);
    }

    public function testRecipe(){
        $user = $this->createUser($this->testFixtures, $this->entityManager);
        $apiToken = $this->getToken($this->client, $this->testFixtures);
        $recipeResponse = $this->createRecipe($this->client, $this->testFixtures, $apiToken);
        $this->assertEquals($this->testFixtures->recipeName,json_decode($recipeResponse->getContent())->name);

        $ingredientRepository = $this->entityManager->getRepository(Ingredient::class);
        $ingredient1 = $ingredientRepository->findOneBy(['name' => $this->testFixtures->ingredient1['name']]);
        $this->assertTrue($ingredient1 instanceof Ingredient);
    }

    public function testAddWeeklyRecipe(){
        $user = $this->createUser($this->testFixtures, $this->entityManager);
        $apiToken = $this->getToken($this->client, $this->testFixtures);
        $recipeResponse = $this->createRecipe($this->client, $this->testFixtures, $apiToken);
        $weeklyRecipeResponse = $this->addWeeklyRecipe($this->client, $this->testFixtures, $apiToken, json_decode($recipeResponse->getContent())->id);
        $this->assertEquals("Recipe was added",$weeklyRecipeResponse->getContent(),);
    }
}