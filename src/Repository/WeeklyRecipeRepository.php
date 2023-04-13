<?php

namespace App\Repository;

use App\DTO\RecipesResponseDto;
use App\Entity\Recipe;
use App\Entity\WeeklyRecipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeeklyRecipe>
 *
 * @method WeeklyRecipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeeklyRecipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeeklyRecipe[]    findAll()
 * @method WeeklyRecipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeeklyRecipeRepository extends ServiceEntityRepository
{

    private WeeklyIngredientsRepository $weeklyIngredientsRepository;
    public function __construct(ManagerRegistry $registry, WeeklyIngredientsRepository $weeklyIngredientsRepository)
    {
        parent::__construct($registry, WeeklyRecipe::class);
        $this->weeklyIngredientsRepository = $weeklyIngredientsRepository;
    }

    public function save(WeeklyRecipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WeeklyRecipe $entity, bool $flush = false): void
    {
        // remove ingredients
        $this->weeklyIngredientsRepository->removeFromRecipe($entity->getRecipe());

        // remove from list
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllResponse(): ArrayCollection
    {
        $weeklyRecipes = $this->findAll();
        $weeklyRecipesResponse = new ArrayCollection();

        foreach($weeklyRecipes as $weeklyRecipe){
            $recipeResponseDTO = new RecipesResponseDto($weeklyRecipe->getRecipe());
            $weeklyRecipesResponse->add($recipeResponseDTO);
        }
        return $weeklyRecipesResponse;
    }

    public function buildFromRecipe(Recipe $recipe): Recipe
    {
        $newWeeklyRecipe = new WeeklyRecipe();
        $newWeeklyRecipe->setRecipe($recipe);
        $newWeeklyRecipe->setOwner($recipe->getOwner());
        $this->save($newWeeklyRecipe,true);

        // Add ingredients
        $this->weeklyIngredientsRepository->buildFromRecipe($recipe);

        return $newWeeklyRecipe->getRecipe();
    }

//    /**
//     * @return WeeklyRecipeController[] Returns an array of WeeklyRecipeController objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WeeklyRecipeController
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
