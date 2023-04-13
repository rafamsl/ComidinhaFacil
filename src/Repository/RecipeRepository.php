<?php

namespace App\Repository;

use App\DTO\RecipesDto;
use App\DTO\RecipesResponseDto;
use App\Entity\Recipe;
use App\Entity\WeeklyRecipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    private UserRepository $userRepository;
    private WeeklyRecipeRepository $weeklyRecipeRepository;
    public function __construct(ManagerRegistry $registry, UserRepository $userRepository, WeeklyRecipeRepository $weeklyRecipeRepository)
    {
        parent::__construct($registry, Recipe::class);

        $this->userRepository = $userRepository;
        $this->weeklyRecipeRepository = $weeklyRecipeRepository;
    }

    public function save(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recipe $entity, bool $flush = false): void
    {
        $weeklyRecipe = $this->weeklyRecipeRepository->findOneBy(['recipe'=>$entity]);

        if($weeklyRecipe instanceof WeeklyRecipe){
           $this->weeklyRecipeRepository->remove($weeklyRecipe);
        }

        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function buildFromDTO(RecipesDto $recipesDto): Recipe
    {
        $newRecipe = new Recipe();
        $newRecipe->setName($recipesDto->getName());
        $newRecipe->setDescription($recipesDto->getDescription());
        $user = $this->userRepository->find($recipesDto->getOwner());
        $newRecipe->setOwner($user);
        $this->save($newRecipe, true);
        return $newRecipe;
    }

    public function findAllResponse(){
        $recipes = $this->findAll();
        $recipesResponse = new ArrayCollection();

        foreach($recipes as $recipe){
            $recipeResponseDTO = new RecipesResponseDto($recipe);
            $recipesResponse->add($recipeResponseDTO);
        }

        return $recipesResponse;

    }

//    /**
//     * @return Recipe[] Returns an array of Recipe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recipe
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
