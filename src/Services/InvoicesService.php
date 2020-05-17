<?php

namespace App\Services;

use App\Entities\Currencies;
use App\Entities\Currency;
use App\Entities\Invoice;
use App\Enumerations\Colors;
use App\Enumerations\SupportedCurrencies;
use App\Helpers\CliPrinter;
use SplFileObject;

class InvoicesService
{
    /**
     * @var CliPrinter
     */
    protected $printer;

    public function __construct(CliPrinter $printer)
    {
        $this->printer = $printer;
    }

    /**
     * @param Invoice[] $invoices
     * @param Currencies $currencies
     * @param string $outputCurrency
     * @param string $vatFilter
     * @return string[]
     */
    public function calculateInvoices(
        array $invoices,
        Currencies $currencies,
        string $outputCurrency,
        string $vatFilter
    )
    {
        $res = [];
        foreach ($invoices as $invoice) {
            if (!empty($invoice->getParentDocument())) {
                if (!$this->checkForMissingParentDocumentId($invoices , $invoice->getParentDocument())) {
                    $this->printer->display(
                        Colors::getColoredString(
                            'Invoice with ID ' . $invoice->getParentDocument() . ' is missing',
                            'red'
                        )
                    );
                    exit(418);
                }
            }

            if ($invoice->getCurrency() === SupportedCurrencies::SUPPORTED_CURRENCIES[strtolower($outputCurrency)]) {
                if (array_key_exists($invoice->getVatNumber(), $res)) {
                    if ($invoice->getType() === 1 || $invoice->getType() === 3) {
                        $res[$invoice->getVatNumber()]['total'] = $res[$invoice->getVatNumber()]['total'] + $invoice->getTotal();
                    } else {
                        $res[$invoice->getVatNumber()]['total'] = $res[$invoice->getVatNumber()]['total'] - $invoice->getTotal();
                    }
                } else {
                    $res[$invoice->getVatNumber()]['customer'] = $invoice->getCustomer();
                    if ($invoice->getType() === 1 || $invoice->getType() === 3) {
                        $res[$invoice->getVatNumber()]['total'] = $invoice->getTotal();
                    } else {
                        $res[$invoice->getVatNumber()]['total'] = 0 - $invoice->getTotal();
                    }
                }
            } else {
                $currencyValue = $currencies->getCurrencyValueByName(
                    SupportedCurrencies::SUPPORTED_CURRENCIES[strtolower($outputCurrency)]
                );
                if (array_key_exists($invoice->getVatNumber(), $res)) {
                    if ($invoice->getType() === 1 || $invoice->getType() === 3) {
                        $res[$invoice->getVatNumber()]['total'] = $res[$invoice->getVatNumber()]['total'] + ($invoice->getTotal() * $currencyValue);
                    } else {
                        $res[$invoice->getVatNumber()]['total'] = $res[$invoice->getVatNumber()]['total'] - ($invoice->getTotal() * $currencyValue);
                    }
                } else {
                    $res[$invoice->getVatNumber()]['customer'] = $invoice->getCustomer();
                    if ($invoice->getType() === 1 || $invoice->getType() === 3) {
                        $res[$invoice->getVatNumber()]['total'] = ($invoice->getTotal() * $currencyValue);
                    } else {
                        $res[$invoice->getVatNumber()]['total'] = 0 - ($invoice->getTotal() * $currencyValue);
                    }
                }
            }
        }

        if ($vatFilter) {
            $res = array_filter($res, function ($element) use ($vatFilter) {
                return $element == $vatFilter;
            }, ARRAY_FILTER_USE_KEY);
        }

        return $res;
    }

    /**
     * @param Invoice[] $invoices
     * @param string $parentDocument
     * @return bool
     */
    private function checkForMissingParentDocumentId(array $invoices ,string $parentDocument)
    {
        $found = false;
        foreach ($invoices as $invoice) {
            if ($invoice->getDocumentNumber() == $parentDocument) {
                $found = true;
            }
        }

        return $found;
    }

    /**
     * @param string $filePath
     * @return Invoice[]
     */
    public function createArrayFromCSVFile(string $filePath)
    {
        try {
            $file = new SplFileObject($filePath);
            $file->setFlags(SplFileObject::READ_CSV);
        } catch (\Exception $e) {
            $this->printer->display(Colors::getColoredString($e->getMessage(), 'red'));
            exit(418);
        }

        $invoicesList = [];
        foreach ($file as list($customer, $vatNumber, $documentNumber, $type, $parentDocument, $currency, $total)) {
            if ($file->key() > 0) {
                $invoicesList[] = new Invoice($customer, $vatNumber, $documentNumber, $type, $currency, $total, $parentDocument);
            }
        }

        return $invoicesList;
    }

    /**
     * @param string $currenciesString
     * @return Currencies
     */
    public function createCurrenciesObjectsByString(string $currenciesString)
    {
        $currencies = [];
        $currencyStringsArray = explode(',', $currenciesString);
        if (is_array($currencyStringsArray) && !empty($currencyStringsArray)) {
            foreach ($currencyStringsArray as $currencyString) {
                $currencyArray = explode(':', $currencyString);
                if (is_array($currencyArray) && !empty($currencyArray)) {
                    if ($this->checkCurrencyString(strtolower($currencyArray[0]))) {
                        $currency = new Currency($currencyArray[0], $currencyArray[1]);
                        $currencies[] = $currency;
                    }
                }
            }
        }

        $res = new Currencies();
        $res->setCurrencies($currencies);

        return $res;
    }

    /**
     * @param string $outputCurrency
     * @return string
     */
    public function checkCurrencyString(string $outputCurrency)
    {
        if (gettype($outputCurrency) !== 'string') {
            $this->printer->display(Colors::getColoredString('Output currency must be a string', 'red'));
            exit(418);
        }

        if (!SupportedCurrencies::checkIfCurrencyIsSupported(strtolower($outputCurrency))) {
            $this->printer->display(Colors::getColoredString('Output currency is not supported', 'red'));
            exit(418);
        }

        return SupportedCurrencies::SUPPORTED_CURRENCIES[strtolower($outputCurrency)];
    }

    /**
     * @param string $customerFilter
     * @return string
     */
    public function extractVatFilter(string $customerFilter)
    {
        if (substr($customerFilter, 0, 2 ) !== "--") {
            $this->printer->display(
                Colors::getColoredString(
                    'Optional parameter for vat filter must start with --',
                    'red')
            );
            exit(418);
        }

        $customerFilter = str_replace('--', '', $customerFilter);
        $customerFilter = explode('=', $customerFilter);

        if (!is_array($customerFilter) ||
            !array_key_exists(1, $customerFilter) ||
            empty($customerFilter[1]) ||
            $customerFilter[0] !== 'vat'
        ) {
            $this->printer->display(
                Colors::getColoredString(
                    'Optional parameter for vat is not properly defined (--vat=123456789)',
                    'red')
            );
            exit(418);
        }

        return $customerFilter[1];
    }
}