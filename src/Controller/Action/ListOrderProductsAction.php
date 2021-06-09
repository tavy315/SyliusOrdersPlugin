<?php

declare(strict_types=1);

namespace Tavy315\SyliusOrdersPlugin\Controller\Action;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tavy315\SyliusOrdersPlugin\Context\OrderContextInterface;
use Twig\Environment;

final class ListOrderProductsAction
{
    private OrderContextInterface $orderContext;

    private ?int $productLimit;

    private Environment $twig;

    public function __construct(OrderContextInterface $orderContext, Environment $twig, ?int $productLimit)
    {
        $this->orderContext = $orderContext;
        $this->productLimit = $productLimit;
        $this->twig = $twig;
    }

    public function __invoke(string $document, Request $request): Response
    {
        $order = $this->orderContext->getCustomerOrder($document);

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'html' => $this->twig->render('@Tavy315SyliusOrdersPlugin/Account/CustomerOrder/Grid/_products.html.twig', [
                    'order'    => $order,
                    'products' => $this->orderContext->getQuoteProducts($order),
                ]),
            ]);
        }

        return new Response($this->twig->render('@Tavy315SyliusOrdersPlugin/Account/CustomerOrder/Grid/show.html.twig', [
            'order'    => $order,
            'products' => $this->orderContext->getQuoteProducts($order, $this->productLimit),
        ]));
    }
}
