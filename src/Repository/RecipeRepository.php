<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /***
     * Get last recipes
     * @param int $limit
     * @return mixed
     */
    public function findLastRecipes (int $limit)
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->orderBy('r.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findRecipesByCategory($category)
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.category = :searchCategory')
            ->setParameter('searchCategory', $category)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findSuggestionByCategory($categoryId)
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.category = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->setMaxResults(3)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findRecipesByUser($userId)
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
