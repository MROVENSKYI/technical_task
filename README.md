Introduction

This project handles commission calculations based on various strategies. It processes transactions and calculates the
commission based on the type and context of each transaction.

Running the System

1.Requirements:
-Docker installed on your system.
-A PHP project with a suitable Dockerfile.

2.Clone the repository:

      git clone <repository-url>

3.Navigate to the project directory in your terminal.

      cd <project-directory>

4.Before you run your PHP script, you can change in the src\Config\FileConstants.php default currency.
If you don't specify a currency, it will use 'EUR' as the default.

5.To process transactions and calculate commissions, run:

      docker build -t project:latest .

      docker run --rm project:latest

6.Running Tests
To run the tests, execute:

      docker run project composer run test

Note: the example output is calculated base on the following exchange rates: EUR:USD - 1:1.1497, EUR:JPY - 1:129.53. Application use https://developers.paysera.com/tasks/api/currency-exchange-rates.
Exchange rates are slightly different, so the commission will be different too.

