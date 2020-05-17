<?php

namespace App\Tests;

use App\Entities\Invoice;
use ArgumentCountError;
use PHPUnit\Framework\TestCase;
use TypeError;

class InvoiceTest extends TestCase
{
    public function testCanCreateNewInvoiceObject()
    {
        $this->assertInstanceOf(
            Invoice::class,
            new Invoice('test', 1, 1, 1, 'test', 1)
        );
    }

    public function testCannotCreateNewInvoiceObjectAndThrowsNewArgumentCountError()
    {
        $this->expectException(ArgumentCountError::class);
        new Invoice();
    }

    public function testCannotSetInvoiceCustomerAndThrowsNewArgumentCountError()
    {
        $this->expectException(TypeError::class);
        $invoice = new Invoice('test', 1, 1, 1, 'test', 1);
        $invoice->setCustomer([]);
    }
}