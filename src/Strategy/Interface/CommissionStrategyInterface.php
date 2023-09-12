<?php

declare(strict_types=1);

namespace src\Strategy\Interface;

use src\Model\Transaction;

interface CommissionStrategyInterface
{
    public function canApply(Transaction $transaction);

    public function calculate(Transaction $transaction);
}