<?php

namespace App\Controllers\Console;

use App\Helpers\CliPrinter;
use App\Enumerations\Colors;
use App\Services\InvoicesService;

class StartCliApp
{
    /**
     * @var CliPrinter
     */
    protected $printer;

    /**
     * @var InvoicesService
     */
    protected $invoiceService;

    public function __construct()
    {
        $this->printer = new CliPrinter();
        $this->invoiceService = new InvoicesService($this->printer);
    }

    public function calculateInvoices(array $argv)
    {
        if (count($argv) < 3) {
            $this->printer->display(
                Colors::getColoredString('Not enough arguments provided', 'red')
            );
            exit(418);
        }

        $invoices = [];
        $currencies = [];
        $outputCurrency = '';
        $vatFilter = '';
        foreach ($argv as $position => $inputField) {
            $inputField = htmlspecialchars(escapeshellcmd($inputField));
            switch ($position) {
                case 1:
                    $invoices = $this->invoiceService->createArrayFromCSVFile($inputField);
                    break;
                case 2:
                    $currencies = $this->invoiceService->createCurrenciesObjectsByString($inputField);
                    break;
                case 3:
                    $outputCurrency = $this->invoiceService->checkCurrencyString($inputField);
                    break;
                case 4:
                    $vatFilter = $this->invoiceService->extractVatFilter($inputField);
                    break;
            }
        }

        $responseArray = $this->invoiceService->calculateInvoices($invoices, $currencies, $outputCurrency, $vatFilter);

        foreach ($responseArray as $response) {
            $this->printer->out('Customer ' . $response['customer'] . ' - ' . $response['total'] . ' ' . $outputCurrency);
            $this->printer->newline();
        }
    }
}