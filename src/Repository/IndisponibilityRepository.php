<?php

namespace App\Repository;

use App\Entity\Car;
use App\Entity\Indisponibility;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Indisponibility|null find($id, $lockMode = null, $lockVersion = null)
 * @method Indisponibility|null findOneBy(array $criteria, array $orderBy = null)
 * @method Indisponibility[]    findAll()
 * @method Indisponibility[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndisponibilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Indisponibility::class);
    }

    /**
     * @param Car       $car
     * @param string    $startingAt
     * @param string    $endingAt
     *
     * @return bool
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isCarAvailable(Car $car, $startingAt, $endingAt): bool
    {
        $startingAt = new \DateTime($startingAt);
        $endingAt = new \DateTime($endingAt);

        if ($endingAt < $startingAt) {
            return false;
        }

        $query = $this->createQueryBuilder('indisponibility')
            ->select('COUNT(indisponibility.id)')
            ->andWhere('indisponibility.car = :car')
            ->andWhere(
                //Case 1 x[x]
                '(:startingAt <= indisponibility.startingAt AND :endingAt <= indisponibility.endingAt AND :endingAt >= indisponibility.startingAt) OR '.
                //Case 2 [xx]
                '(:startingAt >= indisponibility.startingAt AND :endingAt <= indisponibility.endingAt) OR '.
                //Case 3 [x]x
                '(:startingAt >= indisponibility.startingAt AND :startingAt <= indisponibility.endingAt AND :endingAt >= indisponibility.endingAt) OR '.
                //Case 4 x[]x
                '(:startingAt <= indisponibility.startingAt AND :endingAt >= indisponibility.endingAt)'
            )
            ->setParameter('car', $car)
            ->setParameter('startingAt', $startingAt)
            ->setParameter('endingAt', $endingAt)
            ->getQuery()
        ;

        return 0 === (int) $query->getSingleScalarResult();
    }
}
