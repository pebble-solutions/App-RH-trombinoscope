<?php

namespace App\Repository;

use App\Entity\Etat;
use App\Entity\PlageHoraire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Etat>
 *
 * @method Etat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etat[]    findAll()
 * @method Etat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etat::class);
    }

    public function save(Etat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Etat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouver l'etat a partir de la plage horaire
     * @param PlageHoraire $plageHoraire la plage horaire pour laquelle chercher l'état
     * @return Etat|null l'état correspondant à la plage horaire donnée ou null si aucun n'a été trouvé
     */
    public function findByPlageHoraires(PlageHoraire $plageHoraire): ?Etat
    {
        // Créer une instance de QueryBuilder pour construire la requête SQL
        $qb = $this->createQueryBuilder('e')

            // Joindre la table PlageHoraire en utilisant l'alias 'ph'
            ->join('e.plageHoraires', 'ph')

            // Ajouter une condition à la requête qui filtre les résultats en fonction de l'identifiant de la plage horaire
            ->andWhere('ph.id = :plageHoraireId')
            ->setParameter('plageHoraireId', $plageHoraire->getId());

        // Récupérer le résultat de la requête en appelant getQuery() sur l'objet QueryBuilder et getOneOrNullResult() pour récupérer un seul objet ou null
        return $qb->getQuery()->getOneOrNullResult();
    }

//    /**
//     * @return Etat[] Returns an array of Etat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Etat
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
