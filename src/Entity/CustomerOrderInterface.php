<?php

namespace Tavy315\SyliusOrdersPlugin\Entity;

use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface CustomerOrderInterface extends ResourceInterface
{
    public function getId(): ?int;

    public function getCustomer(): ?CustomerInterface;

    public function setCustomer(?CustomerInterface $customer): void;

    public function getDocumentDate(): ?\DateTimeInterface;

    public function setDocumentDate(?\DateTimeInterface $documentDate): void;

    public function getDocumentNo(): ?string;

    public function setDocumentNo(?string $documentNo): void;

    /**
     * @return array<array>
     */
    public function getProducts(): array;

    /**
     * @param array<array> $products
     */
    public function setProducts(array $products): void;

    public function getTotalAmount();

    public function setTotalAmount($totalAmount): void;

    public function getUrl(): ?string;

    public function setUrl(?string $url): void;
}
