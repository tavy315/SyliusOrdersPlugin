<?php

declare(strict_types=1);

namespace Tavy315\SyliusOrdersPlugin\Context;

use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Tavy315\SyliusOrdersPlugin\Entity\CustomerOrderInterface;
use Tavy315\SyliusOrdersPlugin\Entity\CustomerOrderProduct;
use Tavy315\SyliusOrdersPlugin\Repository\CustomerOrderRepositoryInterface;
use Tavy315\SyliusOrdersPlugin\Repository\ProductRepositoryInterface;

final class OrderContext implements OrderContextInterface
{
    private CustomerOrderRepositoryInterface $customerOrderRepository;

    private ProductRepositoryInterface $productRepository;

    private ShopperContextInterface $shopperContext;

    private TokenStorageInterface $tokenStorage;

    public function __construct(
        CustomerOrderRepositoryInterface $customerOrderRepository,
        ProductRepositoryInterface $productRepository,
        ShopperContextInterface $shopperContext,
        TokenStorageInterface $tokenStorage

    ) {
        $this->customerOrderRepository = $customerOrderRepository;
        $this->productRepository = $productRepository;
        $this->shopperContext = $shopperContext;
        $this->tokenStorage = $tokenStorage;
    }

    public function getCustomerOrder(string $documentNo): CustomerOrderInterface
    {
        $customerOrder = $this->customerOrderRepository->findOneByCustomerAndDocument($this->getCustomer(), $documentNo);

        if ($customerOrder === null) {
            throw new NotFoundHttpException();
        }

        return $customerOrder;
    }

    /**
     * @return array<CustomerOrderProduct>
     */
    public function getQuoteProducts(CustomerOrderInterface $order, ?int $limit = null): array
    {
        $products = [];

        $orderProducts = \array_slice($order->getProducts(), 0, $limit);

        foreach ($orderProducts as $product) {
            $customerOrderProduct = CustomerOrderProduct::fromArray($product);

            if ($product['no'] !== '') {
                $customerOrderProduct->product = $this->productRepository
                    ->createShopListQueryBuilder(
                        $this->shopperContext->getChannel(),
                        $this->shopperContext->getLocaleCode(),
                        [ $product['no'] ]
                    )
                    ->getQuery()
                    ->getOneOrNullResult();
            }

            $products[] = $customerOrderProduct;
        }

        return $products;
    }

    private function getCustomer(): CustomerInterface
    {
        $token = $this->tokenStorage->getToken();

        if ($token === null) {
            throw new AuthenticationCredentialsNotFoundException('The token storage contains no authentication token. One possible reason may be that there is no firewall configured for this URL.');
        }

        $user = $token ? $token->getUser() : null;

        if (!($user instanceof ShopUserInterface)) {
            throw new AccessDeniedHttpException();
        }

        $customer = $user->getCustomer();

        if (!($customer instanceof CustomerInterface)) {
            throw new AccessDeniedHttpException();
        }

        return $customer;
    }
}
