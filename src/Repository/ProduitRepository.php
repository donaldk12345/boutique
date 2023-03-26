<?php

namespace App\Repository;

use App\Entity\Filtre;
use App\Entity\Produit;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    /**
     * 
     *@var PaginatorInterface
     */
    private $paginator;
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {

        parent::__construct($registry, Produit::class);
        $this->paginator = $paginator;
    }

    public function add(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Produit[] Returns an array of Produit objects
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

    //    public function findOneBySomeField($value): ?Produit
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * 
     *@return PaginationInterface
     */
    public function findWithFiltre(Filtre $filtre): PaginationInterface
    {
        $query = $this->createQueryBuilder('p')->select('c', 'p')->join('p.category', 'c')->andWhere('p.active=true');

        if (!empty($filtre->categories)) {
            $query = $query->andWhere('c.id IN (:categories)')->setParameter('categories', $filtre->categories);
        }

        if (!empty($filtre->val)) {
            $query = $query->orWhere('p.nom LIKE :val OR p.description LIKE :val')->setParameter('val', "%{$filtre->val}%");
        }

        if (!empty($filtre->prixMin)) {
            $query = $query->andWhere('p.prix >= :prixMin')->setParameter('prixMin', $filtre->prixMin);
        }
        if (!empty($filtre->prixMax)) {
            $query = $query->andWhere('p.prix <= :prixMax')->setParameter('prixMax', $filtre->prixMax);
        }
        if (!empty($filtre->promo)) {
            $query = $query->andWhere('p.promo=true');
        }

        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $filtre->page,
            8
        );
    }

    public function findByMin()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.promo')
            ->where('p.promo=true')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }
}
