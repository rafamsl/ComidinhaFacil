<?php

namespace App\Controller\WeeklyIngredients;

use App\DTO\WeeklyIngredientDTO;
use App\Entity\Ingredient;
use App\Entity\User;
use App\Repository\IngredientRepository;
use App\Repository\UserRepository;
use App\Repository\WeeklyIngredientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeeklyIngredientsController extends AbstractController
{

    #[Route('/api/weekly_ingredients_sum', name:'api_weekly_ingredients_sum(answer', methods: ['GET'])]
    public function allWeeklyIngredients(Request $request, UserRepository $userRepository): Response
    {
        $userId = $request->query->get('userId');
        $user = $userRepository->find($userId);

        if(!$user instanceof User){
            return new Response('User not found');
        }

        $weeklyIngredientsResponse = $user->getWeeklyIngredientsResponse();

        return new Response(json_encode($weeklyIngredientsResponse->getValues(), JSON_PRETTY_PRINT));
    }
    #[Route('/api/remove_weekly_ingredient', name:'api_remove_weekly_ingredient', methods: ['DELETE'])]
    public function removeWeeklyIngredient(Request $request, UserRepository $userRepository, WeeklyIngredientsRepository $weeklyIngredientsRepository, IngredientRepository $ingredientRepository): Response
    {
        $userId = $request->query->get('userId');
        $ingredientName = $request->query->get('ingredientName');

        $user = $userRepository->find($userId);
        $ingredient = $ingredientRepository->findOneBy(['name'=>$ingredientName]);

        if(!$ingredient instanceof Ingredient){
            return new Response('Ingredient not found');
        }

        if(!$user instanceof User){
            return new Response('User not found');
        }

        $weeklyIngredientsRepository->removeByIngredient($ingredient, true);

        return new Response('Ingredient removed');
    }
}