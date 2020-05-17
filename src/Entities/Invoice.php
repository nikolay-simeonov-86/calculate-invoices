<?php

namespace App\Entities;

class Invoice
{
    /**
     * @var string
     */
    private $customer;

    /**
     * @var int
     */
    private $vatNumber;

    /**
     * @var int
     */
    private $documentNumber;

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var int
     */
    private $total;

    /**
     * @var int
     */
    private $parentDocument = null;

    /**
     * Invoice constructor.
     * @param string $customer
     * @param int $vatNumber
     * @param int $documentNumber
     * @param int $type
     * @param string $currency
     * @param int $total
     * @param string|null $parentDocument
     */
    public function __construct(
        string $customer,
        int $vatNumber,
        int $documentNumber,
        int $type,
        string $currency,
        int $total,
        string $parentDocument = null
    )
    {
        $this->setCustomer($customer);
        $this->setVatNumber($vatNumber);
        $this->setDocumentNumber($documentNumber);
        $this->setType($type);
        $this->setCurrency($currency);
        $this->setTotal($total);
        if ($parentDocument) {
            $this->setParentDocument($parentDocument);
        }
    }

    /**
     * @return string
     */
    public function getCustomer(): string
    {
        return $this->customer;
    }

    /**
     * @param string $customer
     */
    public function setCustomer(string $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return int
     */
    public function getVatNumber(): int
    {
        return $this->vatNumber;
    }

    /**
     * @param int $vatNumber
     */
    public function setVatNumber(int $vatNumber): void
    {
        $this->vatNumber = $vatNumber;
    }

    /**
     * @return int
     */
    public function getDocumentNumber(): int
    {
        return $this->documentNumber;
    }

    /**
     * @param int $documentNumber
     */
    public function setDocumentNumber(int $documentNumber): void
    {
        $this->documentNumber = $documentNumber;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getParentDocument(): ?int
    {
        return $this->parentDocument;
    }

    /**
     * @param int $parentDocument
     */
    public function setParentDocument(int $parentDocument): void
    {
        $this->parentDocument = $parentDocument;
    }
}