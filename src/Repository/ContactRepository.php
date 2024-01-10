<?php

namespace App\Repository;

use App\Entity\Contact;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Contact>
 *
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    private $paginator;
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Contact::class);
        $this->paginator= $paginator;
    }

    public function findBySearch(SearchData $searchData): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->leftJoin('c.customFields', 'cf');

        if (!empty($searchData->q)) {
            $queryBuilder
                ->andWhere('c.name LIKE :q OR c.firstname LIKE :q OR c.email LIKE :q OR c.phoneNumber LIKE :q OR cf.name LIKE :q OR cf.value LIKE :q')
                ->setParameter('q', '%' . $searchData->q . '%');
        }

        $query = $queryBuilder->getQuery();
        $results = $query->getResult();

        return $this->paginator->paginate($results, $searchData->page, 9);

    }
}
