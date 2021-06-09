<?php

declare(strict_types=1);

namespace Tavy315\SyliusOrdersPlugin\Context;

use Tavy315\SyliusOrdersPlugin\Entity\CustomerOrderInterface;
use Tavy315\SyliusOrdersPlugin\Entity\CustomerOrderProduct;

interface OrderContextInterface
{
    public function getCustomerOrder(string $documentNo): CustomerOrderInterface;

    /**
     * @return array<CustomerOrderProduct>
     */
    public function getQuoteProducts(CustomerOrderInterface $order, ?int $limit = null): array;
}
