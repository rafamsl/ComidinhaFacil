<?php

namespace App\Controller\Auth;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Repository\ApiTokenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TokenController extends AbstractController
{
    #[Route('/get_token', name:'app_get_token', methods: ['POST'])]
    public function getToken(ApiTokenRepository $apiTokenRepository): object
    {
        $user = $this->getUser();

        if(!$user instanceof User){
            return $this->json([
                'error' => 'User not found'
            ]);
        }

        $apiToken = new ApiToken($user);
        $apiTokenRepository->save($apiToken, true);

        return $this->json([
            'user.id' => $user->getId(),
            'apiToken' => $apiToken->getToken(),
            'expiresAt' => $apiToken->getExpiresAt()
        ]);
    }

}