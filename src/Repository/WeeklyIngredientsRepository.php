<?php

namespace App\Repository;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\User;
use App\Entity\WeeklyIngredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeeklyIngredient>
 *
 * @method WeeklyIngredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeeklyIngredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeeklyIngredient[]    findAll()
 * @method WeeklyIngredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeeklyIngredientsRepository extends ServiceEntityRepository
{
    private IngredientRepository $ingredientRepository;

    public function __construct(ManagerRegistry $registry, IngredientRepository $ingredientRepository)
    {
        parent::__construct($registry, WeeklyIngredient::class);
        $this->ingredientRepository = $ingredientRepository;
    }

    public function save(WeeklyIngredient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WeeklyIngredient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function removeByIngredient(Ingredient $ingredient, bool $flush = false): void
    {

        $weeklyIngredients = $this->findBy(['ingredient'=>$ingredient]);
        if($weeklyIngredients){
            foreach ($weeklyIngredients as  $weeklyIngredient){
                $this->remove($weeklyIngredient);
            }
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }

    }

    public function buildFromRecipe(Recipe $recipe): void
    {
        $recipeIngredients = $recipe->getRecipeIngredients();
        $owner = $this->getEntityManager()->getReference(User::class, $recipe->getOwner()->getId());

        foreach ($recipeIngredients as $recipeIngredient){
            $newWeeklyIngredient = new WeeklyIngredient();
            $newWeeklyIngredient->setIngredient($recipeIngredient->getIngredient());
            $newWeeklyIngredient->setRecipe($recipe);
            $newWeeklyIngredient->setOwner($owner);
            $owner->addWeeklyIngredient($newWeeklyIngredient);
            $newWeeklyIngredient->setAmount($recipeIngredient->getAmount());
            $this->save($newWeeklyIngredient);
        }
        $this->getEntityManager()->flush();
    }

    public function removeFromRecipe(Recipe $recipe): void
    {
        $weeklyIngredients = $this->findBy(['recipe'=>$recipe]);

        foreach ($weeklyIngredients as $weeklyIngredient){
            $this->remove($weeklyIngredient);
        }
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return WeeklyIngredient[] Returns an array of WeeklyIngredient objects
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

//    public function findOneBySomeField($value): ?WeeklyIngredient
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
