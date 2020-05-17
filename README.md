# Calculate invoices
#### Simple PHP Cli app hat calculates the sum of invoices by id

## Example usage

Command:

```bash
./console import path-to-file/import.csv EUR:1,USD:0.987,GBP:0.878 GBP --vat=123456789
```

Example output:

```
Customer Test - 147.58 EUR
Customer Clippings - 180.89 ERU
```

## Demo data

The demo data can be found in the [`data.csv` file](./data.csv).

Invoice types:

- 1 = invoice
- 2 = credit note
- 3 = debit note