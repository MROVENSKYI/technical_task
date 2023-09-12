<?php

declare(strict_types=1);

namespace src\Service;

use src\Config\FileConstants;

class CurrencyFormatterService
{
    public function format(string $amount, string $currency): string
    {
        $decimalPlaces = $this->getDecimalPlaces($currency);

        return number_format((float)$amount, $decimalPlaces, '.', '');
    }

    private function getDecimalPlaces(string $currency): int
    {
        return FileConstants::CURRENCY_DECIMALS[$currency] ?? FileConstants::DECIMALS_NUMBER;
    }

}
