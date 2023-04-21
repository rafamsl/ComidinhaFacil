<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/api/login', name:'app_login',methods: ['POST'])]
    public function login(){
        if(!$this->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->json([
                'error' => 'not authenticated'
            ]);
        }
        return $this->json([
            'user' => $this->getUser() ?$this->getUser()->getId():null
        ]);
    }
}