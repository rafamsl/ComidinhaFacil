<?php

namespace App\Controller\WeeklyRecipe;

use App\Entity\Recipe;
use App\Entity\WeeklyRecipe;
use App\Repository\RecipeRepository;
use App\Repository\WeeklyRecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeeklyRecipeController extends AbstractController
{
    #[Route('/api/weekly_recipes', name:'api_weekly_recipes', methods: ['GET'])]
    public function allWeeklyRecipes(WeeklyRecipeRepository $weeklyRecipeRepository){
        $weeklyRecipes = $weeklyRecipeRepository->findAllResponse();
        return new Response(json_encode($weeklyRecipes->getValues(), JSON_PRETTY_PRINT));
    }

    #[Route('/api/add_weekly_recipe/{recipeId}', name:'api_add_weekly_recipes', methods: ['POST'])]
    public function addWeeklyRecipe(int $recipeId, RecipeRepository $recipeRepository,WeeklyRecipeRepository $weeklyRecipeRepository){
        $recipe = $recipeRepository->find($recipeId);

        # Check if Recipe exists
        if(!$recipe instanceof Recipe){
            return new Response('Recipe not found');
        }

        $weeklyRecipe = $weeklyRecipeRepository->findOneBy(['recipe' => $recipe->getId()]);
        # Check if Recipe was in this list
        if($weeklyRecipe instanceof WeeklyRecipe){
            return new Response('Recipe was already in the list');
        }

        $newWeeklyRecipe = $weeklyRecipeRepository->buildFromRecipe($recipe);
        return new Response('Recipe was added');
    }

    #[Route('/api/remove_weekly_recipe/{recipeId}', name:'api_remove_weekly_recipes', methods: ['DELETE'])]
    public function removeWeeklyRecipe(int $recipeId, WeeklyRecipeRepository $weeklyRecipeRepository, RecipeRepository $recipeRepository){
        $recipe = $recipeRepository->find($recipeId);

        # Check if Recipe exists
        if(!$recipe instanceof Recipe){
            return new Response('Recipe not found');
        }

        $weeklyRecipe = $weeklyRecipeRepository->findOneBy(['recipe' => $recipe->getId()]);
        # Check if Recipe was in this list
        if(!$weeklyRecipe instanceof WeeklyRecipe){
            return new Response('Recipe was not in the list');
        }

        $weeklyRecipeRepository->remove($weeklyRecipe,true);
        return new Response('Recipe removed');
    }

}