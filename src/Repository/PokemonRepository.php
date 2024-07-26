<?php

namespace App\Repository;

use App\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pokemon>
 */
class PokemonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokemon::class);
    }

    //    /**
    //     * @return Pokemon[] Returns an array of Pokemon objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Pokemon
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }



    public function findLikeName($search)
    {
        //crée un constructeur de requete dans la table Pokemon
        $queryBuilder = $this->createQueryBuilder('pokemon');

        // la requete selectionne la table de donnée
        $query = $queryBuilder->select('pokemon')
            // Définit la zone de recherche et la souplesse
            ->where('pokemon.name LIKE :search')
            // Définit les paramètres de la requete et l'emplacement des lettres
            ->setParameter('search', '%' . $search . '%')
            ->getQuery();

        $pokemons = $query->getArrayResult();

        return $pokemons;
    }

}