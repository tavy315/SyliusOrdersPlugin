<?php

declare(strict_types=1);

namespace Tavy315\SyliusOrdersPlugin\Controller\Action;

use Sylius\Component\Core\Context\ShopperContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tavy315\SyliusOrdersPlugin\Context\OrderContextInterface;
use Tavy315\SyliusOrdersPlugin\Entity\CustomerOrderInterface;
use Tavy315\SyliusOrdersPlugin\Entity\CustomerOrderProduct;
use Tavy315\SyliusOrdersPlugin\Repository\ProductRepositoryInterface;
use Twig\Environment;

final class ListOrderProductsAction
{
    private OrderContextInterface $orderContext;

    private ProductRepositoryInterface $productRepository;

    private ShopperContextInterface $shopperContext;

    private Environment $twig;

    public function __construct(
        OrderContextInterface $orderContext,
        ProductRepositoryInterface $productRepository,
        Environment $twig,
        ShopperContextInterface $shopperContext
    ) {
        $this->orderContext = $orderContext;
        $this->productRepository = $productRepository;
        $this->shopperContext = $shopperContext;
        $this->twig = $twig;
    }

    public function __invoke(string $document, Request $request): Response
    {
        $order = $this->orderContext->getCustomerOrder($document);

        return new Response($this->twig->render('@Tavy315SyliusOrdersPlugin/Account/CustomerOrder/Grid/products.html.twig', [
            'order'    => $order,
            'products' => $this->getProducts($order),
        ]));
    }

    /**
     * @return array<CustomerOrderProduct>
     */
    private function getProducts(CustomerOrderInterface $order): array
    {
        $products = [];

        foreach ($order->getProducts() as $product) {
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
}
