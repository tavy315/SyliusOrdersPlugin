<?php

declare(strict_types=1);

namespace Tavy315\SyliusOrdersPlugin\Context;

use Tavy315\SyliusOrdersPlugin\Entity\CustomerOrderInterface;

interface OrderContextInterface
{
    public function getCustomerOrder(string $documentNo): CustomerOrderInterface;
}
