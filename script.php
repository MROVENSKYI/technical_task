<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use src\Config\FileConstants;
use src\Repository\TransactionRepository;
use src\Service\CommissionCalculator;
use src\Service\CsvReader;
use src\Service\CurrencyConverter;
use src\Service\CurrencyFormatterService;
use src\Service\Math;
use src\Strategy\BusinessWithdrawStrategy;
use src\Strategy\DepositCommissionStrategy;
use src\Strategy\Factory;
use src\Strategy\PrivateWithdrawalStrategy;

$filename = $argv[1];

$rates = isset($argv[2]) ? json_decode($argv[2], true) : null;

if (!$rates) {
    $ratesData = file_get_contents('https://developers.paysera.com/tasks/api/currency-exchange-rates');
    $rates = json_decode($ratesData, true);
}
$exchangeRates = $rates['rates'];
$mathService = new Math(FILECONSTANTS::DECIMALS_SCALE);
$currencyConverter = new CurrencyConverter($exchangeRates, $mathService);
mainScript($filename, FILECONSTANTS::DEFAULT_CURRENCY, $currencyConverter);

function mainScript($filename, $defaultCurrency, $currencyConverter): void
{
    $csvReader = new CsvReader();

    $mathService = new Math(FILECONSTANTS::DECIMALS_SCALE);
    $currencyFormatterService = new CurrencyFormatterService();
    $transactionTracker = new TransactionRepository($currencyConverter, $mathService);

    $depositStrategy = new DepositCommissionStrategy($mathService);
    $businessWithdrawStrategy = new BusinessWithdrawStrategy($mathService);
    $privateWithdrawStrategy = new PrivateWithdrawalStrategy($transactionTracker, $currencyConverter, $mathService);

    $strategyFactory = new Factory($depositStrategy, $businessWithdrawStrategy, $privateWithdrawStrategy);

    $calculator = new CommissionCalculator($strategyFactory);

    try {
        foreach ($csvReader->read($filename) as $transaction) {
            $result = $calculator->calculate($transaction);
            $transactionTracker->addTransaction($transaction);
            echo $currencyFormatterService->format($result['amount'], $result['currency']), PHP_EOL;
        }
    } catch (Exception $e) {
        echo sprintf("Error: %s%s", $e->getMessage(), PHP_EOL);
    }
}