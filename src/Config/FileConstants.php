<?php

declare(strict_types=1);

namespace src\Config;

class FileConstants
{
    const DEFAULT_CURRENCY = 'EUR';
    const BUSINESS_WITHDRAW_FEE = '0.005';
    const DEPOSIT_FEE = '0.0003';
    const FREE_WITHDRAWALS_LIMIT = '3';
    const FREE_WITHDRAWAL_AMOUNT_LIMIT = '1000.00';
    const PRIVATE_COMMISSION_RATE = '0.003';
    public const BUFFER_SIZE = 1000;
    public const COLUMNS_COUNT = 6;
    public const CURRENCY_DECIMALS = [
        'JPY' => 0,
        'USD' => 2,
        'EUR' => 2
    ];
    public const DECIMALS_NUMBER = 2;

    public const DECIMALS_SCALE = 3;
}
