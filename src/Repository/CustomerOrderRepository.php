<?php

declare(strict_types=1);

namespace Tavy315\SyliusOrdersPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;
use Tavy315\SyliusOrdersPlugin\Entity\CustomerOrderInterface;

class CustomerOrderRepository extends EntityRepository implements CustomerOrderRepositoryInterface
{
    public function findOneByCustomerAndDocument(CustomerInterface $customer, string $documentNo): ?CustomerOrderInterface
    {
        return $this->createQueryBuilder('r')
                    ->where('r.customer = :customer')
                    ->andWhere('r.documentNo = :documentNo')
                    ->setParameter('customer', $customer)
                    ->setParameter('documentNo', $documentNo)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    public function createByCustomerQueryBuilder($customerId): QueryBuilder
    {
        return $this->createQueryBuilder('o')
                    ->andWhere('o.customer = :customerId')
                    ->setParameter('customerId', $customerId);
    }
}
