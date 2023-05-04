<?php

namespace App\Tests\functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\DataFixtures\TestFixtures;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

trait UserTest
{
    protected function createUser(TestFixtures $testFixtures, EntityManagerInterface $entityManager): User
    {
        # Create User
        $user = new User();
        $user->setEmail($testFixtures->email);
        $user->setPassword($testFixtures->hashedPassword);
        $user->setRoles(['ROLE_API']);

        # Save User
        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }

    protected function getToken(Client $client, TestFixtures $testFixtures): array
    {

        $userAuth = $client->request('POST','/get_token',[
            'json' => [
                'email' => $testFixtures->email,
                'password' => $testFixtures->password,
            ],
        ])->getContent();
        return json_decode($userAuth,true);
    }

}