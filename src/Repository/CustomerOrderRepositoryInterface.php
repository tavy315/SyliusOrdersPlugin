<?php

declare(strict_types=1);

namespace Tavy315\SyliusOrdersPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Tavy315\SyliusOrdersPlugin\Entity\CustomerOrderInterface;

interface CustomerOrderRepositoryInterface extends RepositoryInterface
{
    public function findOneByCustomerAndDocument(CustomerInterface $customer, string $documentNo): ?CustomerOrderInterface;

    public function createByCustomerQueryBuilder($customerId): QueryBuilder;
}
