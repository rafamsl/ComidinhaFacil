<?php

namespace App\Controller\Ingredients;

use App\DTO\IngredientsDto;
use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IngredientsController extends AbstractController
{
    #[Route('/api/ingredients', name:'api_ingredients', methods: ['GET'])]
    public function allIngredients(IngredientRepository $ingredientRepository){
        $ingredients = $ingredientRepository->findAll();
        $ingredientsDTO = [];
        foreach($ingredients as $ingredient){
            $ingredientsDTO[] = new IngredientsDto($ingredient->getName(),$ingredient->getUnit());
        }
        return new JsonResponse($ingredientsDTO);
    }


}