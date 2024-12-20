<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function add(Contact $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Contact $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countByTheSameName()
    {
        return $this->createQueryBuilder('c')
            ->select('c.name, COUNT(c.id) as nameCount')
            ->groupBy('c.name')
            ->having('nameCount > 1')
            ->getQuery()
            ->getResult();

    }

    public function findByTheSameName(string $name)
    {
        return $this->createQueryBuilder('c')
            ->select("c.id, c.name, c.email, c.birthday")
            ->where('c.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getScalarResult();
    }    

    public function findPhonesByTheSameName(string $name)
    {
        return $this->createQueryBuilder('c')
            ->select("c.phones")
            ->where('c.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult();
    }    

}
