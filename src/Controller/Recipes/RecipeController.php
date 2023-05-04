<?php

namespace App\Controller\Recipes;

use App\DTO\IngredientsDto;
use App\DTO\RecipeIngredientsDTO;
use App\DTO\RecipesDto;
use App\Entity\Recipe;
use App\Repository\ApiTokenRepository;
use App\Repository\IngredientRepository;
use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeRepository;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    #[Route('/api/recipes', name:'api_new_recipe', methods: ['POST'])]
    public function newRecipe(Request $request, RecipeRepository $recipeRepository, IngredientRepository $ingredientRepository, RecipeIngredientRepository $recipeIngredientRepository){
        $recipeContent = json_decode($request->getContent(),true);

        $newRecipeDTO = new RecipesDto(
            $recipeContent['name'],
            $recipeContent['description'],
            $recipeContent['owner']
        );
        $newRecipe = $recipeRepository->buildFromDTO($newRecipeDTO);

        $ingredientsList = $recipeContent['ingredients'];

        foreach ($ingredientsList as $recipeIngredient){
            $ingredient = $ingredientRepository->findOneBy(['name' => $recipeIngredient['name']]);

            if(!$ingredient){
                $newIngredientDTO = new IngredientsDto($recipeIngredient['name'],$recipeIngredient['unit']);
                $ingredient = $ingredientRepository->buildFromDTO($newIngredientDTO);
            }
            $amount = $recipeIngredient['amount'];
            $newRecipeIngredientDTO = new RecipeIngredientsDTO($ingredient,$newRecipe,$amount);
            $recipeIngredientRepository->buildFromDTO($newRecipeIngredientDTO);
        }
        $responseObject = [
            'id' => $newRecipe->getId(),
            'name' => $newRecipe->getName(),
            'recipeIngredients' => $newRecipe->getRecipeIngredientsDTO()->getValues(),
            'description' => $newRecipe->getDescription(),
            'owner' => [
                'id' => $newRecipe->getOwner()->getId(),
                'email' => $newRecipe->getOwner()->getEmail()
            ]
        ];

        return new Response(json_encode($responseObject, JSON_PRETTY_PRINT));
    }

    #[Route('/api/recipes/{recipeId}', name:'api_delete_recipe', methods: ['DELETE'])]
    public function deleteRecipe(int $recipeId, Request $request, RecipeRepository $recipeRepository, IngredientRepository $ingredientRepository, RecipeIngredientRepository $recipeIngredientRepository){
        $recipe = $recipeRepository->find($recipeId);

        if(!$recipe instanceof Recipe){
            return new Response('Recipe not found');
        }

        try {
            $recipeRepository->remove($recipe,true);
        } catch(Exception $exception){
            return new Response('There was an error: '.$exception);
        }
        return new Response('Recipe deleted');
    }

    /**
     * @Route("/recipes", name="recipes_new", methods={"POST"})
     */
    public function newFrontRecipe(Request $request, ApiTokenRepository $apiTokenRepository, IngredientRepository $ingredientRepository): Response
    {
        $form = $this->createFormBuilder()
            ->add('name')
            ->add('description')
            ->add('ingredients')
            ->getForm();

        $form->handleRequest($request);
        $apiToken = str_replace('Bearer ','',$request->headers->get('Authorization'));
        $owner = $apiTokenRepository->findOneBy(['apiToken' => $apiToken]);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $newRecipeDTO = new RecipesDto(
                $formData['name'],
                $formData['description'],
                #USER,
            );

            foreach($formData['ingredients'] as $recipeIngredient) {
                $ingredient = $ingredientRepository->findOneBy(['name' => $recipeIngredient['name']]);

                if(!$ingredient){
                    $newIngredientDTO = new IngredientsDto($recipeIngredient['name'],$recipeIngredient['unit']);
                    $ingredient = $ingredientRepository->buildFromDTO($newIngredientDTO);
                }
                $amount = $recipeIngredient['amount'];
                $newRecipeIngredientDTO = new RecipeIngredientsDTO($ingredient,$newRecipe,$amount);
                $recipeIngredientRepository->buildFromDTO($newRecipeIngredientDTO);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('recipe_show', [
                'id' => $recipe->getId(),
            ]);
        }

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('recipe_show', [
                'id' => $recipe->getId(),
            ]);
        }

        return $this->render('recipe_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}