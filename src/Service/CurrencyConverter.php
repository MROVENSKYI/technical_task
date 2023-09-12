<?php

declare(strict_types=1);

namespace src\Service;

use Exception;

class CurrencyConverter
{
    private array $rates;
    private Math $mathService;

    public function __construct(array $rates, Math $mathService)
    {
        $this->rates = $rates;
        $this->mathService = $mathService;
    }

    /**
     * @throws Exception
     */
    public function convert(string $amount, string $fromCurrency, string $toCurrency): string
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $amountInEur = $this->toEUR($amount, $fromCurrency);

        return $this->fromEUR($amountInEur, $toCurrency);
    }

    /**
     * @throws Exception
     */
    public function getRate(string $currency): string
    {
        if (!isset($this->rates[$currency])) {
            throw new Exception("Currency rate for {$currency} not found.");
        }

        return (string)$this->rates[$currency];
    }

    /**
     * @throws Exception
     */
    private function toEUR(string $amount, string $fromCurrency): string
    {
        if ($fromCurrency === 'EUR') {
            return $amount;
        }
        $rate = $this->getRate($fromCurrency);
        return $this->mathService->divide($amount, $rate);
    }

    /**
     * @throws Exception
     */
    private function fromEUR(string $amountInEur, string $toCurrency): string
    {
        if ($toCurrency === 'EUR') {
            return $amountInEur;
        }
        $rate = $this->getRate($toCurrency);
        return $this->mathService->multiply($amountInEur, $rate);
    }
}
