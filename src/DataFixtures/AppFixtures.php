<?php

namespace App\DataFixtures;

use App\Factory\IngredientFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne(['email' => 'rafael.msl81@gmail.com']);
        UserFactory::createMany(1);
        IngredientFactory::createMany(1);

        $manager->flush();
    }
}
